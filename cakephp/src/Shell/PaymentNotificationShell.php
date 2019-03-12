<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class PaymentNotificationShell extends Shell {
    public function main() {
		$mem_payment_tbl = TableRegistry::get("MembershipPayment");
		$gym_member_tbl = TableRegistry::get("GymMember");
		$data = $mem_payment_tbl->find("all")->where(["paid_amount < membership_amount"]);
		if(!empty($data))
		{
			foreach($data as $row)
			{
				$dueAmount = $row["membership_amount"] - $row["paid_amount"];
				$members = $gym_member_tbl->find("all")->where(["id"=>$row["member_id"]])->select(["email","first_name"])->toArray();
				if (!empty($members))
				{
					foreach($members as $member)
					{
						$this->out($member["email"]);
						$this->out($member["first_name"]);
						$this->out($dueAmount);
					}
				}
			}
		}
    }
}