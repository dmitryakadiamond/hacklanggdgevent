<?

define('XL', "XL");
define('L', "L");
define('M', "M");
define('S', "S");


class ContestWinnerFinder
{

    var $finder_mode;

    /*
     * I wish I could set params in some short form !
     * Also I wish I could tell that detection_mode is an integer !
     * */
    function __construct($finder_mode=1)
    {
        $this->finder_mode = (int)$finder_mode;
    }

    private function shuffle_assoc($list) {
        if (!is_array($list)) return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key)
            $random[$key] = $list[$key];

        return $random;
    }

    private function shuffle_assoc_slow($list)
    {
        $ret = [];
        while(sizeof($list)!=0){
            foreach($list as $k=>$v){
                if(mt_rand(0, 10000) == 745){
                    unset($list[$k]);
                    $ret[$k] = $v;
                }
            }
        }

        return $ret;
    }

    private function matchLists($available_tshirt_list, $pretenders_list)
    {
        $ret = [];
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

    public function findWinners($available_tshirt_list, $pretenders_list)
    {
        if($this->finder_mode==1){
            // I wish I could run next two methods async !
            $pretenders_list = $this->shuffle_assoc($pretenders_list);
            $available_tshirt_list = $this->shuffle_assoc($available_tshirt_list); // as we don't want to always start from one size
        }else{
            $pretenders_list = $this->shuffle_assoc_slow($pretenders_list);
            $available_tshirt_list = $this->shuffle_assoc_slow($available_tshirt_list);
        }

        return $this->matchLists($available_tshirt_list, $pretenders_list);
    }

}


$available_tshirt_list = [XL=>1, S=>2, M=>2];
$pretenders_list = ['John Smith'=>XL, 'Bill Noname'=>M, 'Random Dude'=>S, 'George Martin'=>XL, 'Kewin Mitnick'=>S, 'John Doe'=>XL, 'Bob Unknown'=>S];

$cwf = new ContestWinnerFinder(2);
echo "<pre>Contest winners:<br>";
print_r($cwf->findWinners($available_tshirt_list, $pretenders_list));