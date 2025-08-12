from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
import time
import sqlite3
from pathlib import Path

DB_PATH = Path("product-finder/database/database.sqlite")

def scrape_asters():
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")

    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
    url = "https://www.astersonline.com/collections/all"
    print(f"\n🔍 Visiting: {url}")
    driver.get(url)

    # Keep clicking the 'Show more' button until it's gone or unclickable
    max_clicks = 111
    click_count = 0

    while click_count < max_clicks:
        try:
            show_more_btn = driver.find_element(By.CLASS_NAME, "snize-pagination-load-more")
            if show_more_btn.is_displayed() and show_more_btn.is_enabled():
                print(f"🔁 Clicking 'Show more' button (click {click_count + 1})...")
                driver.execute_script("arguments[0].click();", show_more_btn)
                time.sleep(2)
                click_count += 1
            else:
                break
        except Exception:
            print("✅ No more 'Show more' button or error occurred.")
            break

    time.sleep(1)
    product_blocks = driver.find_elements(By.CSS_SELECTOR, "li.snize-product")
    print(f"📦 Found {len(product_blocks)} product blocks")

    products = []

    for p in product_blocks:
        try:
            name = p.find_element(By.CLASS_NAME, "snize-title").text.strip()
            price = p.find_element(By.CLASS_NAME, "snize-price").text.strip()
            link = p.find_element(By.CSS_SELECTOR, "a.snize-view-link").get_attribute("href")
            image = p.find_element(By.CSS_SELECTOR, "img.snize-item-image").get_attribute("src")

            products.append({
                "name": name,
                "price": price,
                "link": link,
                "image": image
            })
        except Exception as e:
            print("⚠️ Skipped product due to error:", e)

    driver.quit()
    return products


def insert_into_sqlite(products):
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()

    for product in products:
        try:
            cursor.execute("""
            INSERT INTO products (name, price, link, image, store, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, datetime('now'), datetime('now'))
        """, (
            product['name'],
            product['price'],
            product['link'],
            product['image'],
            'Asters'
        ))
        except Exception as e:
            print("⚠️ Error inserting product:", e)

    conn.commit()
    cursor.close()
    conn.close()
    print(f"✅ Inserted {len(products)} products into SQLite")


if __name__ == "__main__":
    start_time = time.time()
    data = scrape_asters()
    print(f"✅ Scraped {len(data)} products from Asters")
    insert_into_sqlite(data)

    elapsed = time.time() - start_time
    if elapsed > 60:
        print(f"\n⏱️ Done in {int(elapsed // 60)} minutes and {int(elapsed % 60)} seconds")
    else:
        print(f"\n⏱️ Done in {int(elapsed)} seconds")



