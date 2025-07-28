from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
import time
import mysql.connector


def scrape_sonee_with_selenium(pages=3):
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")

    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
    results = []

    for page in range(1, pages + 1):
        url = f"https://www.sonee.com.mv/collections/all?page={page}"
        print(f"\n🔍 Visiting: {url}")
        driver.get(url)
        time.sleep(2)

        # Find product containers
        products = driver.find_elements(By.XPATH, "//div[contains(@class, 'grid__item')]")
        print(f"Found {len(products)} products on page {page}")

        for p in products:
            try:
                # Product name (linked text)
                name_el = p.find_element(By.XPATH, ".//a[contains(@href, '/products/')]")
                name_lines = [line.strip() for line in name_el.text.strip().split("\n")]
                name = next((line for line in name_lines if "MVR" not in line and "LOCK" not in line), name_lines[0])
                link = name_el.get_attribute("href")

                # Price (look for any element with MVR inside)
                price_el = p.find_element(By.XPATH, ".//*[contains(text(), 'MVR')]")
                price = price_el.text.strip()

                # Image (optional)
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
                print("⚠️ Non-product container skipped.")

    driver.quit()
    return results

def insert_into_mysql(products):
    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="product_finder"
    )
    cursor = conn.cursor()

    for product in products:
        try:
            cursor.execute("""
                INSERT INTO products (name, price, link, image, created_at, updated_at)
                VALUES (%s, %s, %s, %s, NOW(), NOW())
            """, (
                product['name'],
                product['price'],
                product['link'],
                product['image']
            ))
        except Exception as e:
            print("❌ Error inserting product:", e)

    conn.commit()
    cursor.close()
    conn.close()
    print(f"✅ Inserted {len(products)} products into MySQL")


if __name__ == "__main__":
    data = scrape_sonee_with_selenium()
    insert_into_mysql(data)
    print(f"\n✅ Total products scraped: {len(data)}")
    for item in data[:5]:
        print(item)


