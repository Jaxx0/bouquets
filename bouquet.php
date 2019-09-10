<?php
class bouquet {
	private $spec_code;
	private $name;
	private $size;
	private $current_count;
	private $total;
	private $quantities = [];

	function __construct($specs) {
		if(preg_match("/^([A-Z])(L|S)(([1-9]\d*[a-z])+)([1-9]\d*)*$/", $specs, $bouquet_specs) === 0){
			throw new Exception("Incorrect spec format", 1);
		}
		// var_dump($bouquet_specs);
		$bouquet_specs_len = count($bouquet_specs);

		$this->spec_code = $specs;
		$this->name = $bouquet_specs[1];
		$this->size = $bouquet_specs[2];
		$this->current_count = 0;
		$this->total = (int)$bouquet_specs[5];

		$any_quantity = $this->total;
		while(!empty($bouquet_specs[3])){
			preg_match("/([1-9]\d*)([a-z])/", $bouquet_specs[3], $flower_specs);
			$bouquet_specs[3] = preg_replace("/^([1-9]\d*)([a-z])/", "", $bouquet_specs[3]);

			$this->quantities[$flower_specs[2]] = [
				"current" => 0,
				"total" => (int)$flower_specs[1]
			];
			$any_quantity -= (int)$flower_specs[1];
		}
		$this->quantities["any"] = [
			"current" => 0,
			"total" => $any_quantity
		];
	}

	public function add_flower($specie, $size) {
		if ($size !== $this->size) {
			return false;
		}

		if (
			isset($this->quantities[$specie]) &&
			$this->quantities[$specie]["current"] < $this->quantities[$specie]["total"]
		){
			$this->quantities[$specie]["current"]++;
			$this->current_count++;
		}
		elseif ($this->quantities["any"]["current"] < $this->quantities["any"]["total"]) {
			$this->quantities["any"]["current"]++;
			$this->current_count++;
		}else{
			return false;
		}
		return true;
	}

	public function ready() {
		if($this->current_count > $this->total){
			throw new Exception("Error: Bouquet limit exceeded", 1);
		}
		return ($this->current_count === $this->total);
	}

	public function get_spec_code() {
		return $this->spec_code;
	}
}