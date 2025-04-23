from celery import Celery

app = Celery('tasks', broker='redis://localhost')


@app.task
def process_claim(claim_id):
    print(f"Processing claim {claim_id}")

test_claims = [
    {"id": 1, "status": "open"},
    {"id": 2, "status": "open"},
    {"id": 2, "status": "closed"},
    {"id": 3, "status": "open"},
    {"id": 3, "status": "pending"},
    {"id": 3, "status": "open"},
    {"id": 3, "status": "closed"}
]

for test_claim in test_claims:
    process_claim(test_claim["id"])
