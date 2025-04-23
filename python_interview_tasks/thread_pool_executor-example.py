from concurrent.futures import ThreadPoolExecutor

def process_claim(claim):
    print(f"Processing claim {claim['id']}")

claims = [{ "id": i, "status": "open" if i % 2 == 0 else "closed" } for i in range(10)]

with ThreadPoolExecutor(max_workers=5) as executor:
    executor.map(process_claim, claims)
