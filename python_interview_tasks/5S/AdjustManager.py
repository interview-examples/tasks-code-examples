import typing
from collections import defaultdict

class AdjustManager:
    def __init__(self):
        self._adjustments = []
        self._claims = {}
        self._lob_round_robin = defaultdict(int)


    def add_adjustment(self, adjuster_id: str, lobs: typing.List[str]) -> None:
        self.remove_adjustment(adjuster_id)
        self._adjustments.append((adjuster_id, lobs))
        self._lob_round_robin = defaultdict(int)


    def remove_adjustment(self, adjuster_id: str) -> None:
        self._adjustments = [
            adj for adj in self._adjustments if adj[0] != adjuster_id
        ]
        self._claims = {cid: aid for cid, aid in self._claims.items() if aid != adjuster_id}

    def get_adjuster_to_handle_claim(self, lob: str, claim_id: str) -> str:
        candidates = [adj[0] for adj in self._adjustments if lob in adj[1]]
        if not candidates:
            return ""
        idx = self._lob_round_robin[lob] % len(candidates)
        chosen = candidates[idx]
        self._lob_round_robin[lob] += 1
        self._claims[claim_id] = chosen
        return chosen

    def get_claims_for_adjuster(self, adjuster_id: str) -> typing.List[str]:
        return [cid for cid, aid in self._claims.items() if aid == adjuster_id]

if __name__ == "__main__":
    mgr = AdjustManager()
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
