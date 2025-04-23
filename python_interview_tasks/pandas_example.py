import pandas as pd

df = pd.DataFrame([
    {"id": 1, "status": "open"},
    {"id": 2, "status": "closed"},
    {"id": 3, "status": "open"}
])

print(df.groupby("status").size())
