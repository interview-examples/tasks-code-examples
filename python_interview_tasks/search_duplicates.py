def find_duplicates(claims):
    seen = set()
    duplicates = set()
    for claim in claims:
        if claim["id"] in seen:
            duplicates.add(claim["id"])
        seen.add(claim["id"])
    return list(duplicates), list(seen)

test_claims = [
    {"id": 1, "status": "open"},
    {"id": 2, "status": "open"},
    {"id": 2, "status": "closed"},
    {"id": 3, "status": "open"},
    {"id": 3, "status": "pending"},
    {"id": 3, "status": "open"},
    {"id": 3, "status": "closed"}
]

print(find_duplicates(test_claims))
