import requests
from bs4 import BeautifulSoup
import time

BASE_URL = "https://www.sonee.com.mv"
CATEGORY_URL = "https://www.sonee.com.mv/collections/all?page={}"

HEADERS = {
    "User-Agent": "Mozilla/5.0"
}

def scrape_sonee_products(max_pages=5):
    products = []

    for page in range(1, max_pages + 1):
        print(f"Scraping page {page}...")
        url = CATEGORY_URL.format(page)
        response = requests.get(url, headers=HEADERS)
        if response.status_code != 200:
            print(f"Failed to fetch page {page}")
            continue

        soup = BeautifulSoup(response.text, "html.parser")
        product_containers = soup.select(".productitem")  # more general selector

        for product in product_containers:
            try:
                title_tag = product.select_one(".productitem--title")
                price_tag = product.select_one(".price--main")

                name = title_tag.get_text(strip=True) if title_tag else "N/A"
                price = price_tag.get_text(strip=True) if price_tag else "N/A"
                link = BASE_URL + title_tag.find("a")["href"] if title_tag else "N/A"

                image_tag = product.select_one("img")
                if image_tag and image_tag.has_attr("src"):
                    image = image_tag["src"]
                    if image.startswith("//"):
                        image = "https:" + image
                else:
                    image = None

                products.append({
                    "name": name,
                    "price": price,
                    "link": link,
                    "image": image
                })

            except Exception as e:
                print(f"Error parsing product: {e}")

        time.sleep(1)

    return products


if __name__ == "__main__":
    product_data = scrape_sonee_products()
    for p in product_data[:5]:  # show first 5
        print(p)

