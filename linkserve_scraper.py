# linkserve_scraper.py
import time
import sqlite3
from pathlib import Path
from urllib.parse import urljoin

import requests
from bs4 import BeautifulSoup

BASE = "https://shop.linkserve.mv"
LISTING = f"{BASE}/collections/all"
DB_PATH = Path("product-finder/database/database.sqlite")

HEADERS = {"User-Agent": "Mozilla/5.0"}

def scrape_linkserve(max_pages=9999, sleep_s=1.0):
    total, page = 0, 1
    products = []

    while page <= max_pages:
        url = LISTING if page == 1 else f"{LISTING}?page={page}"
        print(f"🔍 Visiting: {url}")
        r = requests.get(url, headers=HEADERS, timeout=30)
        if r.status_code != 200:
            print(f" HTTP {r.status_code} on page {page}. Stopping.")
            break

        soup = BeautifulSoup(r.text, "html.parser")

        # Each product is in a div.product-item so select from there
        items = soup.select("div.product-item")
        print(f" Found {len(items)} product blocks on page {page}")

        if not items:
            break

        for it in items:
            try:
                # title + link
                a_title = it.select_one("a.product-item__title")
                if not a_title:
                    # fallback to image link wrapper
                    a_title = it.select_one("a.product-item__image-wrapper")
                if not a_title:
                    continue

                name = a_title.get_text(strip=True)
                href = a_title.get("href", "")
                link = urljoin(BASE, href)

                # Price
                price_el = it.select_one(".product-item__price-list .price")
                price = price_el.get_text(strip=True) if price_el else ""

                # Image
                img_el = it.select_one("img.product-item__primary-image")
                image = img_el.get("src") if img_el else ""
                if image.startswith("//"):
                    image = "https:" + image

                products.append({
                    "name": name,
                    "price": price,
                    "link": link,
                    "image": image
                })
            except Exception as e:
                print(" Skipped one item:", e)

        total += len(items)
        page += 1
        time.sleep(sleep_s)  # be polite

    print(f"\n Scraped {len(products)} products from LinkServe")
    return products


def insert_into_sqlite(rows):
    conn = sqlite3.connect(DB_PATH)
    cur = conn.cursor()

    inserted = 0
    for p in rows:
        try:
            cur.execute("""
                INSERT OR IGNORE INTO products (name, price, link, image, store, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, datetime('now'), datetime('now'))
            """, (p["name"], p["price"], p["link"], p["image"], "LinkServe"))
            inserted += cur.rowcount
        except Exception as e:
            print(" Insert error:", e)

    conn.commit()
    cur.close()
    conn.close()
    print(f" Inserted {inserted} new rows into SQLite")


if __name__ == "__main__":
    start = time.time()
    data = scrape_linkserve()
    insert_into_sqlite(data)
    elapsed = time.time() - start
    print(f"\n Done in {int(elapsed//60)} min {int(elapsed%60)} sec" if elapsed > 60
          else f"\n Done in {int(elapsed)} sec")
