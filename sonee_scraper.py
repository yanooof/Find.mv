import time
import sqlite3
from pathlib import Path
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager

DB_PATH = Path("product-finder/database/database.sqlite") 

start_time = time.time()

def scrape_sonee_paginated(max_empty_pages=3):
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")

    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
    results = []

    page = 1
    empty_page_count = 0

    while empty_page_count < max_empty_pages:
        url = f"https://www.sonee.com.mv/collections/all?page={page}"
        print(f"\nVisiting: {url}")
        driver.get(url)
        time.sleep(2)

        products = driver.find_elements(By.XPATH, "//div[contains(@class, 'grid__item')]")
        print(f"Found {len(products)} product blocks on page {page}")

        if len(products) < 10:
            empty_page_count += 1
        else:
            empty_page_count = 0

        for p in products:
            try:
                name_el = p.find_element(By.XPATH, ".//a[contains(@href, '/products/')]")
                name_lines = [line.strip() for line in name_el.text.strip().split("\n")]
                name = next((line for line in name_lines if "MVR" not in line and "LOCK" not in line), name_lines[0])
                link = name_el.get_attribute("href")

                price_el = p.find_element(By.XPATH, ".//*[contains(text(), 'MVR')]")
                price = price_el.text.strip()

                try:
                    image_el = p.find_element(By.XPATH, ".//img")
                    image = image_el.get_attribute("src")
                except:
                    image = None

                results.append({
                    "name": name,
                    "price": price,
                    "link": link,
                    "image": image
                })
            except Exception:
                continue

        page += 1

    driver.quit()
    return results


def insert_into_sqlite(products):
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()

    for product in products:
        try:
            cursor.execute("""
                INSERT INTO products (name, price, link, image, created_at, updated_at)
                VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
            """, (
                product['name'],
                product['price'],
                product['link'],
                product['image']
            ))
        except Exception as e:
            print(" Error inserting product:", e)

    conn.commit()
    cursor.close()
    conn.close()
    print(f"\n Inserted {len(products)} products into SQLite")


if __name__ == "__main__":
    data = scrape_sonee_paginated()
    insert_into_sqlite(data)
    print(f"\n Total products scraped: {len(data)}")

    end_time = time.time()
    elapsed = end_time - start_time

    if elapsed > 60:
        minutes = int(elapsed // 60)
        seconds = int(elapsed % 60)
        print(f"\nScraping completed in {minutes} minutes and {seconds} seconds")
    else:
        print(f"\nScraping completed in {int(elapsed)} seconds")



