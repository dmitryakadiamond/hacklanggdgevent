<?hh //strict 

class ContestWinnerFinder
{


    public function __construct(public int $finder_mode=1)
    {
    }

    private function shuffle_assoc<T>(Map<string, T> $list): Map<string, T> 
    {
	$keys = $list->toKeysArray();
        shuffle($keys);
        $random = Map{};
        foreach ($keys as $key)
            $random[$key] = $list[$key];

        return $random;
    }

    private function shuffle_assoc_slow<T>(Map<string, T> $list, T $nuller): Map<string, T>
    {
	$ret = Map{};
	$cntr=0;
        while($cntr<sizeof($list)){
            foreach($list as $k=>$v){
                if(mt_rand(0, 10000) == 745 && $v!=$nuller){
			$list->set($k, $nuller);
			$cntr++;
			$ret[$k] = $v;
			
                }
            }
        }

        return $ret;
    }

    private function matchLists(Map<string, int> $available_tshirt_list, Map<string, string> $pretenders_list): Map<string, string>
    {
        $ret = Map{};
        $tmp = 0;
        foreach($available_tshirt_list as $tshirt_size=>$num){
           $tmp = $num;
           foreach($pretenders_list as $pretender=>$pretender_size){
               if($pretender_size==$tshirt_size){
                   $ret[$pretender] = $pretender_size;
                   if(!--$tmp) break;
               }
           }
        }

        return $ret;
    }

    public function findWinners(Map<string, int> $available_tshirt_list, Map<string, string> $pretenders_list):Map<string, string>
    {
        if($this->finder_mode==1){
            // I wish I could run next two methods async !
            $pretenders_list = $this->shuffle_assoc($pretenders_list);
            $available_tshirt_list = $this->shuffle_assoc($available_tshirt_list); // as we don't want to always start from one size
        }else{
            $pretenders_list = $this->shuffle_assoc_slow($pretenders_list, "0");
            $available_tshirt_list = $this->shuffle_assoc_slow($available_tshirt_list, 0);
	}


        return $this->matchLists($available_tshirt_list, $pretenders_list);
    }

    public function main():void
    {
	$available_tshirt_list = Map{"XL"=>1, "S"=>2, "M"=>2};
	$pretenders_list = Map{'John Smith'=>"XL", 'Bill Noname'=>"M", 'Random Dude'=>"S", 'George Martin'=>"XL", 'Kewin Mitnick'=>"S", 'John Doe'=>"XL", 'Bob Unknown'=>"S"};

	echo "<pre>Contest winners:<br>";
	$ret = $this->findWinners($available_tshirt_list, $pretenders_list);
	print_r($ret);
    }
}

