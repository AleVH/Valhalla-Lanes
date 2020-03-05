<?php

/**
 * Class Calendar
 * This class deals with the current month so far, to deal with an entire year it needs some modifications (actually quite a lot)
 */

class Calendar {

	private $day;
	private $first_day;
	private $last_day;
	private $month;
	private $month_name;
	private $month_short_name;
	private $year;
	private $date;
	private $month_length;
	private $available_slots;

	public function __construct(){
		$todays = new DateTime('NOW');
		$this->day = date('l', strtotime($todays->format('Y-m-d')));
		$this->first_day = date('l', strtotime((new DateTime('first day of this month'))->format('Y-m-d')));
		$this->last_day = date('l', strtotime((new DateTime('last day of this month'))->format('Y-m-d')));
		$this->month = $todays->format('m');
		$this->month_name = $todays->format('F');
		$this->month_short_name = $todays->format('M');
		$this->year = $todays->format('Y');
		$this->date = $todays->format('d');;
		$this->month_length = cal_days_in_month(CAL_GREGORIAN, $todays->format('m'), $todays->format('Y'));
	}

	public function getDay(){
		return $this->day;
	}

	public function getFirstDay(){
		return $this->first_day;
	}

	public function getLastDay(){
		return $this->last_day;
	}

	public function getMonth(){
		return $this->month;
	}

	public function getMonthName(){
		return $this->month_name;
	}

	public function getMonthShortName(){
		return $this->month_short_name;
	}

	public function getYear(){
		return $this->year;
	}

	public function getDate(){
		return $this->date;
	}

	public function getMonthLength(){
		return $this->month_length;
	}

}
