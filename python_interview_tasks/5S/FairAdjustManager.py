from collections import defaultdict
import typing
import random

class FairAdjustManager:
    def __init__(self):
        self._adjusters = {}
        self._claims = {}
        self._claim_count = defaultdict(int)

    def add_adjustment(self, adjuster_id: str, lobs: typing.List[str]) -> None:
        self._adjusters[adjuster_id] = set(lobs)

    def remove_adjustment(self, adjuster_id: str) -> None:
        if adjuster_id in self._adjusters:
            del self._adjusters[adjuster_id]
        self._claims = {cid: aid for cid, aid in self._claims.items() if aid != adjuster_id}
        self._claim_count.pop(adjuster_id, None)

    def get_adjuster_to_handle_claim(self, lob: str, claim_id: str) -> str:
        candidates = [aid for aid, lobs in self._adjusters.items() if lob in lobs]
        if not candidates:
            return ""
        min_count = min(self._claim_count[aid] for aid in candidates)
        best = [aid for aid in candidates if self._claim_count[aid] == min_count]
        chosen = random.choice(best)
        self._claims[claim_id] = chosen
        self._claim_count[chosen] += 1
        return chosen

    def get_claims_for_adjuster(self, adjuster_id: str) -> typing.List[str]:
        return [cid for cid, aid in self._claims.items() if aid == adjuster_id]

if __name__ == "__main__":
    mgr = FairAdjustManager()
    mgr.add_adjustment("adj1", ["Home",])
    mgr.add_adjustment("adj2", ["Home", "Auto"])
    mgr.add_adjustment("adj3", ["Home", "Auto"])
    mgr.add_adjustment("adj4", ["Auto", ])

    mgr.get_adjuster_to_handle_claim("Home", "claim1")
    mgr.get_adjuster_to_handle_claim("Auto", "claim2")
    mgr.get_adjuster_to_handle_claim("Auto", "claim3")
    mgr.get_adjuster_to_handle_claim("Auto", "claim4")
    mgr.get_adjuster_to_handle_claim("Home", "claim5")
    mgr.get_adjuster_to_handle_claim("Auto", "claim6")
    mgr.get_adjuster_to_handle_claim("Home", "claim7")
    mgr.get_adjuster_to_handle_claim("Auto", "claim8")

    print(f"Claims for adjuster adj1", mgr.get_claims_for_adjuster("adj1"))
    print(f"Claims for adjuster adj2", mgr.get_claims_for_adjuster("adj2"))
    print(f"Claims for adjuster adj3", mgr.get_claims_for_adjuster("adj3"))
    print(f"Claims for adjuster adj4", mgr.get_claims_for_adjuster("adj4"))
    mgr.remove_adjustment("adj1")
    mgr.remove_adjustment("adj3")
    print(f"Claims for adjuster adj1", mgr.get_claims_for_adjuster("adj1"))
    print(f"Claims for adjuster adj2", mgr.get_claims_for_adjuster("adj2"))
    print(f"Claims for adjuster adj3", mgr.get_claims_for_adjuster("adj3"))
    print(f"Claims for adjuster adj4", mgr.get_claims_for_adjuster("adj4"))
