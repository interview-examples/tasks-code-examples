import threading

def process_claim(claim):
    print(f"Processing claim {claim['id']} with status {claim['status']}")

claims = [{ "id": i, "status": "open" if i % 2 == 0 else "closed" } for i in range(10)]
threads = [threading.Thread(target=process_claim, args=(claim,)) for claim in claims]

for t in threads:
    t.start()
for t in threads:
    t.join()

