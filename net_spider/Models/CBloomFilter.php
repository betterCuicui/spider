<?php
class CBloomFilter
{
    private $int_bit_size;
    private $bitmap_arr;
    private $hash_times;
    private $md5_val_size;

    public function init($capacity){
        $capacity = intval($capacity);
        if($capacity > PHP_INT_MAX/2){
            SeasLog::error("设置的值太大，布隆过滤器内存太大。");
            return false;
        }
        if(empty($capacity)){
            return false;
        }
        $this->int_bit_size = 64;
        $this->md5_val_size = (log($capacity*2,16)>intval(log($capacity*2,16)))?(intval(log($capacity*2,16))+1):intval(log($capacity*2,16));
        $this->bitmap_arr = array_fill(0,$capacity*2/8/8,0);
        $this->hash_times = intval(log($capacity,10))-1;
    }

    private function push_num($value){
        $value = intval($value);
        if(empty($value)){
            return false;
        }
        $shang = $value / ($this->int_bit_size);
        $yushu = $value % $this->int_bit_size;
        $offset = 1 << $yushu;
        if($this->bitmap_arr[intval($shang)] & $offset){
            return false;
        }
        else{
            $this->bitmap_arr[intval($shang)] = $this->bitmap_arr[intval($shang)] | $offset;//将bit位置为1
        }
        return true;
    }

    public function push($value){
        if(empty($value)){
            return false;
        }
        $is_not_exist = false;
        for ($i = 0;$i < $this->hash_times; $i++){
            $md5_temp = md5($value);
            $md5_val = hexdec(substr($md5_temp,0,$this->md5_val_size));
            if(self::push_num($md5_val)){
                $is_not_exist = true;
            }
            $value = $md5_temp;
        }
        return $is_not_exist;
    }

}