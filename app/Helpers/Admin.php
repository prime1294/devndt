<?php
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class Admin {

  public static function checkRedirect($request,$route,$message) {
    if ($request->session()->has('redirecting') && $request->session()->has('redirectingback')) {
      $redirect = $request->session()->get('redirecting');
      $redirectback = $request->session()->get('redirectingback');
      $id = $request->session()->get('redirectingbackid');
      $toid = $request->session()->get('redirectingid');
      $request->session()->forget('redirecting');
      $request->session()->forget('redirectingback');
      $request->session()->forget('redirectingbackid');
      $request->session()->forget('redirectingid');
      if($id != "") {
        return redirect()->route($redirectback,$id)->with('success', $message);
      } else {
        return redirect()->route($redirectback)->with('success', $message);
      }
    } else {
      return redirect()->route($route)->with('success', $message);
    }
  }

    public static  function initials($str) {
        $ret = '';
        foreach (explode(' ', $str) as $word)
            $ret .= strtoupper($word[0]);
        return $ret;
    }

    public static function unauth() {
    return view('admin.v1.auth.unauth');
  }

  public static function getUTC()
  {
    return date('Y-m-d h:i:s',strtotime("now"));
  }

  public static function getlastdateofmonth($month,$year) {
    return date('d',strtotime('last day of this month',strtotime($year.'-'.$month.'-01')));
  }

  public static function getMonthTimestamp() {
    $data[] = date('F, Y');
    for ($i = 1; $i < 12; $i++) {
      $data[] = date('F, Y', strtotime("-$i month"));
    }
    return $data;
  }

  public static function uniqueTransectionId($type,$uid) {
    // PREFIX_UID_DATE_TIME_RAND(1111,9999)
    return config('transection.'.$type)['prefix'].'_'.$uid.'_'.strtotime("now").'_'.rand(1111,9999);
  }

  public static function uniqueStockId($uid,$unique_user_id) {
    return $uid.'_'.$unique_user_id.'_'.rand(11,99).'_'.strtotime("now");
  }

  public static function uniquePCId($uid,$id = "") {
    if($id != "") {
      return $uid.'_'.$id.'_8733883364';
    } else {
      return $uid.'_'.rand(10,99).'_'.strtotime("now");
    }
  }

  public static function FormateStockID($id) {
    return str_replace('_','',$id);
  }

  public static function FormatePCId($id) {
    // return str_replace('_','',$id);
    return 'PCARD'.str_replace('_','',$id);
  }

    public static function FormatePRC($id) {
        $info = \App\Model\StockProcess::find($id);
        return $info->pname;
    }

  public static function FormateStockItemID($id) {
    // $exp = explode('_',$id);
    // return 'STOCK'.$exp[0].$exp[1];
    //return 'STOCK'.str_replace('_','',$id);
      $info = \App\Model\StockItem::find($id);
      return $info->stock_name;
  }

  public static function is_positive_integer($str) {
//  return (is_numeric($str) && $str > 0 && $str == round($str));
     return $str < 0 ? false : true;
  }

  public static function FormateDate($date) {
    return date(config('setting.date_formate'),strtotime($date));
  }

  public static function NumberFormate($amount) {
    return number_format($amount);
  }

  public static function FormateTransection($amount,$color = true) {
    $formatedAmount = "<i class='".config('setting.currency_code')."'></i> ".self::NumberFormate(abs($amount));
    if(self::is_positive_integer($amount)) {
      //postive
        if($color) {
            $html = '<span class="text-bold text-success">' . $formatedAmount . '</span>';
        } else {
            $html = '<span class="text-bold">' . $formatedAmount . '</span>';
        }
    } else {
      //nagative
        if($color) {
            $html = '<span class="text-bold text-danger">' . $formatedAmount . '</span>';
        } else {
            $html = '<span class="text-bold">' . $formatedAmount . '</span>';
        }
    }

    return $html;
  }

  public static function find_in_set($comma,$int)
  {
    $comma_arr = explode(',',$comma);
    if (in_array($int, $comma_arr)) {
      return true;
    } else {
      return false;
    }
  }

  public static function dateDiffrent($end_date) {
      $created = new Carbon($end_date);
      $now = Carbon::now();

      //difference between two dates
      return $created->diff($now)->days;
  }

  //int to rupee
  public static function convertCurrency(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

  //array push in associative array
  public static function array_push_assoc($array, $key, $value){
  	$array[$key] = $value;
  	return $array;
  }

  //encode string
  public static function encode($key,$string) {
        //$key = passwordencrypt; //key to encrypt and decrypts.
        $result = '';
        $test = "";
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            @$test[$char]= ord($char)+ord($keychar);
            $result .= $char;
        }
  	return base64_encode(urlencode($result));
    }

  //decode string
  public static function decode($key,$string) {
      //$key = passwordencrypt; //key to encrypt and decrypts.
      $result = '';
      $string = urldecode(base64_decode($string));
      for($i=0; $i<strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key))-1, 1);
          @$char = chr(ord($char)-ord($keychar));
          $result .= $char;
      }
      return $result;
  }

  public static function getStockReportQuery($search) {

      //old pogramme card query
    //SELECT
    //programme_card.date as report_date,
    //programme_card.id as report_number,
    //"PROGRAMMECARD" as report_type,
    //programme_card.dname as report_user_tbl,
    //0 as report_user,
    //SUM(programme_card_item.quantity) as report_quantity,
    //programme_card.unit_id as report_unit,
    //"" as report_design,
    //"" as report_recive
    //FROM `programme_card`
    //left join programme_card_item on programme_card.pc_unique_number = programme_card_item.pc_unique_number
    //where programme_card_item.user_id = '.$user_id.' AND programme_card.user_id = '.$user_id.' AND programme_card.status = 1 AND programme_card_item.status = 1 AND programme_card.stock_id = '.$stock_id.'
    //GROUP BY programme_card.id


//      UNION
//
//        SELECT
//        settlement.date as report_date,
//        "" as report_number,
//        "SETTLEMENT" as report_type,
//        settlement.remarks as report_user_tbl,
//        0 as report_user,
//        settlement.unit as report_quantity,
//        stock_item.unit as report_unit,
//        settlement.design as report_design,
//        "" as report_recive
//        FROM `settlement`
//        LEFT JOIN stock_item ON stock_item.id = settlement.stock_id
//        where settlement.user_id = '.$user_id.' AND settlement.status = 1 AND stock_item.user_id = '.$user_id.' AND stock_item.status = 1 AND settlement.stock_id = '.$stock_id.' AND stock_item.id = '.$stock_id.'
//        GROUP BY settlement.id

      $user_id = $search['user_id'];
      $stock_id = $search['stock_id'];
      return 'select * from (
        
        SELECT 
        stock_process.date as report_date,
        stock_process.pname as report_number,
        "PROCESS" as report_type,
        "process" as report_user_tbl,
        stock_process.process_id as report_user,
        stock_process_iem.quantity as report_quantity,
        stock_process_iem.mesurement as report_unit,
        stock_process_iem.design_name as report_design,
        (SELECT SUM(qty) FROM process_receive WHERE process_item_id = stock_process_iem.id AND type = 1)  as report_recive
        FROM `stock_process_iem`
        LEFT JOIN stock_process ON stock_process_iem.stock_process_id = stock_process.id
        WHERE stock_process_iem.user_id = '.$user_id.' AND stock_process.user_id = '.$user_id.' AND stock_process_iem.status = 1 AND stock_process.status = 1 AND stock_process_iem.stock_id = '.$stock_id.' AND stock_process.deleted_at IS NULL
        GROUP BY stock_process_iem.id
        
        
        UNION
        
        SELECT 
        ready_stock.date as report_date,
        "" as report_number,
        "READYSTOCK" as report_type,
        "Ready Stock" as report_user_tbl,
        0 as report_user,
        ready_stock.qty as report_quantity,
        ready_stock.unit as report_unit,
        ready_stock.design_name as report_design,
        "" as report_recive
        FROM `ready_stock`
        where ready_stock.user_id = '.$user_id.' AND ready_stock.status = 1 AND ready_stock.stock_no = '.$stock_id.'
        GROUP BY ready_stock.id
        
        UNION
        
    
        SELECT
        programme_card.date as report_date,
        programme_card.pc_name as report_number,
        "PROGRAMMECARD" as report_type,
        programme_card.dname as report_user_tbl,
        0 as report_user,
        SUM(programme_card_item.quantity) as report_quantity,
        programme_card.unit_id as report_unit,
        design.name as report_design,
        (SELECT SUM(qty) FROM programme_card_receive WHERE design_tbl_id = programme_card_design.id AND type = 1) as report_recive 
        FROM `programme_card_design`
        left join programme_card on programme_card_design.pc_unique_number = programme_card.pc_unique_number
        left join programme_card_item on programme_card_design.id = programme_card_item.design_tbl_id
        left join design on programme_card_design.design_id = design.id
        where programme_card_design.user_id = '.$user_id.' AND programme_card.user_id = '.$user_id.' AND programme_card_item.user_id = '.$user_id.' AND programme_card_design.status = 1 AND programme_card_item.status = 1 AND programme_card.stock_id = '.$stock_id.' AND programme_card.deleted_at IS NULL
        GROUP BY programme_card_design.id
        
        UNION
        
        SELECT 
        delivery_challan.date as report_date,
        delivery_challan.dc_name as report_number,
        "CHALLAN" as report_type,
        delivery_challan.business_name as report_user_tbl,
        0 as report_user,
        delivery_challan_item.quantity as report_quantity,
        delivery_challan_item.mesurement as report_unit,
        delivery_challan_item.design_name as report_design,
        "" as report_recive
        FROM `delivery_challan_item`
        LEFT JOIN delivery_challan ON delivery_challan_item.delivery_id = delivery_challan.id
        WHERE delivery_challan_item.user_id = '.$user_id.' AND delivery_challan.user_id = '.$user_id.' AND delivery_challan_item.status = 1 AND delivery_challan.status = 1 AND delivery_challan_item.stock_id = '.$stock_id.' AND delivery_challan.deleted_at IS NULL
        GROUP BY delivery_challan_item.id
        
        UNION
        
        SELECT 
        invoice.date as report_date,
        invoice.delivery_no as report_number,
        "INVOICE" as report_type,
        invoice.business_name as report_user_tbl,
        0 as report_user,
        invoice_item.quantity as report_quantity,
        invoice_item.mesurement as report_unit,
        invoice_item.design_name as report_design,
        "" as report_recive
        FROM `invoice_item`
        LEFT JOIN invoice ON invoice_item.delivery_id = invoice.id
        WHERE invoice_item.user_id = '.$user_id.' AND invoice.user_id = '.$user_id.' AND invoice_item.status = 1 AND invoice.status = 1 AND invoice_item.stock_id = '.$stock_id.'  AND invoice.deleted_at IS NULL
        GROUP BY invoice_item.id
        
        UNION
        
        SELECT 
        stock.as_of as report_date,
        stock.challan_no as report_number,
        "STOCK" as report_type,
        "party" as report_user_tbl,
        stock.party_id as report_user,
        stock_item.total as report_quantity,
        stock_item.unit as report_unit,
        "" as report_design,
        "" as report_recive
        FROM `stock_item`
        LEFT JOIN stock ON stock_item.stock_unique_id = stock.stock_unique_id
        WHERE stock_item.user_id = '.$user_id.' AND stock.user_id = '.$user_id.' AND stock_item.status = 1 AND stock.status = 1 AND stock_item.id = '.$stock_id.'
        GROUP BY stock_item.id
        
        ) a where 1 = 1
        ORDER BY report_date DESC';

  }


  public static function getUserJoinQuery($search) {
      $user_id = $search['user_id'];
      $additional_query = "";
      return 'select * from (
    SELECT id as userid, name as username, photo as userphoto ,"master2" as usertype FROM `agent` WHERE user_id = "'.$user_id.'" and status = 1 and deleted_at IS NULL 
    UNION
    SELECT id as userid, name as username, photo as userphoto ,"master3" as usertype FROM `staff` WHERE user_id = "'.$user_id.'" and status = 1 and deleted_at IS NULL
    UNION
    SELECT id as userid, name as username, photo as userphoto ,"master8" as usertype FROM `karigar` WHERE user_id = "'.$user_id.'" and status = 1 and deleted_at IS NULL
    UNION
    SELECT id as userid, name as username, photo as userphoto ,"master5" as usertype FROM `material` WHERE user_id = "'.$user_id.'" and status = 1 and deleted_at IS NULL
) a where 1 = 1 '.$additional_query.' ORDER BY username ASC';
  }

  public static function getRecPaidSum($search,$type = 1) {
      //this function will return sum all master transection 1 = PAY , 2 = RECi
      $user_id = $search['user_id'];
      $tbl = ['party','agent','staff','karigar','material','process'];
      $query = '';
      foreach($tbl as $row) {
          $query .= 'SELECT
            IF('.$row.'.opening_type = "1", CONCAT("-",'.$row.'.opening_balance), '.$row.'.opening_balance) as transection_amount
            FROM '.$row.'
            WHERE '.$row.'.opening_type = "'.$type.'" AND '.$row.'.user_id = "'.$user_id.'" AND  '.$row.'.opening_asof IS NOT NULL AND '.$row.'.opening_balance != "0.00" AND '.$row.'.deleted_at IS NULL';
          if($row != end($tbl)) {
              $query .= ' UNION ';
          }
      }

      return $query;
  }

  public static function dayBookQuery($search,$date) {
      $user_id = $search['user_id'];

      return 'select *
    from (
    
    SELECT
    payment.id as transection_id,
    payment.created_at as transection_date,
    payment.master_type as master_type,
    payment.master_id as master_id,
    IF(payment.type = "in", "PAYIN", "PAYOUT") as transection_type,
    IF(payment.type = "in", CONCAT("-",payment.amount) , ABS(payment.amount))  as transection_amount,
    IF(payment.type = "in", ABS(payment.amount) , 0)  as money_in,
    IF(payment.type = "out", ABS(payment.amount), 0)  as money_out
    FROM `payment`
    WHERE payment.transection_date = "'.$date.'" AND payment.user_id = '.$user_id.' AND payment.status = 1 AND payment.deleted_at IS NULL
    GROUP BY payment.id
    
    UNION
    
    SELECT
    stock_process.id as transection_id,
    stock_process.created_at as transection_date,
    "master6" as master_type,
    stock_process.process_id as master_id,
    "PROCESS" as transection_type,
    stock_process.grand_total  as transection_amount,
    0  as money_in,
    ABS(process_payment.amount) as money_out
    FROM `process_payment`
    LEFT JOIN stock_process ON process_payment.process_id = stock_process.id
    WHERE process_payment.date = "'.$date.'" AND process_payment.user_id = '.$user_id.' AND stock_process.user_id = '.$user_id.' AND stock_process.status = 1 AND process_payment.deleted_at IS NULL AND stock_process.deleted_at IS NULL
    GROUP BY process_payment.id
    
    UNION
    
    SELECT
    "" as transection_id,
    cash_transection.created_at as transection_date,
    "" as master_type,
    0 as master_id,
    UPPER(cash_transection.type) as transection_type,
    cash_transection.amount  as transection_amount,
    IF(cash_transection.type = "cash3" || cash_transection.type = "cash12",ABS(cash_transection.amount), 0) as money_in,
    IF(cash_transection.type = "cash4",ABS(cash_transection.amount), 0)  as money_out
    FROM `cash_transection`
    WHERE cash_transection.transection_date = "'.$date.'" AND cash_transection.user_id = '.$user_id.' AND cash_transection.status = 1 AND cash_transection.type IN ("cash3","cash4","cash12") AND cash_transection.deleted_at IS NULL
    GROUP BY cash_transection.id
    
    UNION
    
    SELECT
    "" as transection_id,
    bank_transection.created_at as transection_date,
    "" as master_type,
    0 as master_id,
    UPPER(bank_transection.type) as transection_type,
    bank_transection.amount  as transection_amount,
    IF(bank_transection.type = "bank6" || bank_transection.type = "bank14",ABS(bank_transection.amount), 0) as money_in,
    IF(bank_transection.type = "bank7",ABS(bank_transection.amount), 0)  as money_out
    FROM `bank_transection`
    WHERE bank_transection.transection_date = "'.$date.'" AND bank_transection.user_id = '.$user_id.' AND bank_transection.status = 1 AND bank_transection.type IN ("bank6","bank7","bank14") AND bank_transection.deleted_at IS NULL
    GROUP BY bank_transection.id
    
    UNION
    
    SELECT
    invoice.id as transection_id,
    invoice_payment.created_at as transection_date,
    invoice.business_name as master_type,
    0 as master_id,
    "INVOICE" as transection_type,
    invoice.grand_total  as transection_amount,
    ABS(invoice_payment.amount) as money_in,
    0 as money_out
    FROM `invoice_payment`
    LEFT JOIN invoice ON invoice_payment.invoice_id = invoice.id
    WHERE invoice_payment.date = "'.$date.'" AND invoice.user_id = '.$user_id.' AND invoice_payment.user_id = '.$user_id.' AND invoice.status = 1  AND invoice.deleted_at IS NULL AND invoice_payment.deleted_at IS NULL
    GROUP BY invoice_payment.id
    
    UNION
    
    SELECT
    expenses.bill_no as transection_id,
    expenses.created_at as transection_date,
    IF(name IS NULL,"",SUBSTRING_INDEX(name, "_",1)) as master_type,
    IF(name IS NULL,0,SUBSTRING_INDEX(name,"_",-1)) as master_id,
    "EXPENSES" as transection_type,
    expenses.grand_total  as transection_amount,
    0 as money_in,
    ABS(expenses.grand_payment) as money_out
    FROM `expenses`
    WHERE expenses.date = "'.$date.'" AND expenses.user_id = '.$user_id.' AND expenses.status = 1 AND expenses.deleted_at IS NULL
    GROUP BY expenses.id
    
    UNION
    
    SELECT
    "" as transection_id,
    karigar_payment.created_at as transection_date,
    "master8" as master_type,
    karigar_payment.karigar_id as master_id,
    IF(karigar_payment.pay_type = 1, "SALARY", "WITHDRAWAL") as transection_type,
    karigar_payment.amount  as transection_amount,
    0 as money_in,
    ABS(karigar_payment.amount) as money_out
    FROM `karigar_payment`
    WHERE karigar_payment.date = "'.$date.'" AND karigar_payment.user_id = '.$user_id.' AND karigar_payment.status = 1 AND karigar_payment.pay_type IN (1,2) AND karigar_payment.deleted_at IS NULL
    GROUP BY karigar_payment.id
    
    UNION
    
    SELECT
    banks_users.account_no as transection_id,
    banks_users.created_at as transection_date,
    banks_users.name as master_type,
    0 as master_id,
    "BANKOPENING" as transection_type,
    banks_users.opening_balance as transection_amount,
    ABS(banks_users.opening_balance) as money_in,
    0 as money_out
    FROM `banks_users`
    WHERE banks_users.asof = "'.$date.'" AND banks_users.opening_balance != 0 AND banks_users.user_id = '.$user_id.' AND banks_users.status = 1 AND banks_users.deleted_at IS NULL
    
    ) a where 1 = 1
    ORDER BY transection_date ASC';
  }


  public static function masterTransectionQuery($search) {

    //quick note
    //1. not check user status of for opening balance

    $table = $search['table'];
    $user_id = $search['user_id'];
    $id = $search['id'];
    $master_type = $search['master_type'];

      $additional_query = '';

      if(isset($search['type'])) {
          if($search['type'] != "") {
              $additional_query .= ' and a.transection_type = "'.$search['type'].'"';
          }
      }

      if(isset($search['bill_no'])) {
          if($search['bill_no'] != "") {
              $additional_query .= ' and a.transection_recipt_no like "%'.$search['bill_no'].'%"';
          }
      }

      if(isset($search['startdate']) && isset($search['enddate'])) {
          if($search['startdate'] != "" && $search['enddate'] != "") {
              $additional_query .= ' and a.transection_date BETWEEN "'.$search['startdate'].'" and "'.$search['enddate'].'"';
          }
      }


      //addtional query
      $addtional_query_new = '';
//      if($master_type == "master8") {
//          $addtional_query .= '
//          UNION
//            SELECT
//            karigar_payment.id as transection_id,
//            karigar_payment.date as transection_date,
//            IF(karigar_payment.pay_type = 2, "WITHDRAWAL", "SALARY") as transection_type,
//            "0" as transection_amount,
//            IF(karigar_payment.pay_type = 2, "WITHDRAWAL", "SALARY") as transection_remarks,
//            karigar_payment.tid as transection_unique_id,
//            "" as transection_recipt_no,
//            CONCAT("-",karigar_payment.amount) as transection_recive,
//            CONCAT("-",karigar_payment.amount) as transection_paid
//            FROM `karigar_payment`
//            WHERE karigar_payment.user_id = "'.$user_id.'" AND
//            karigar_payment.karigar_id = "'.$id.'" AND
//            karigar_payment.status = 1 AND
//            karigar_payment.amount != "0.00" AND
//            karigar_payment.deleted_at IS NULL
//          ';
//      }

      if($master_type == "master1") {
          $addtional_query_new .= ' UNION SELECT
          invoice.id as transection_id,
          invoice.date as transection_date,
          "INVOICE" as transection_type,
          SUM(invoice.grand_total-invoice.grand_payment) as transection_amount,
          "" as transection_remarks,
          invoice.id as transection_unique_id,
          invoice.id as transection_recipt_no,
          invoice.grand_payment as transection_recive, 
          SUM(invoice.grand_total-invoice.grand_payment) as transection_paid
          FROM `invoice`
          WHERE invoice.user_id = "'.$user_id.'" AND 
          invoice.status = 1 AND 
          invoice.party_id = "'.$id.'" AND 
          invoice.grand_total != "0.00" AND 
          invoice.deleted_at IS NULL  
          group by invoice.id';
      }


      if($master_type == "master6") {
          $addtional_query_new .= ' UNION SELECT
          stock_process.id as transection_id,
          stock_process.date as transection_date,
          "PROCESS" as transection_type,
          SUM(stock_process.grand_payment-stock_process.final_total) as transection_amount,
          "" as transection_remarks,
          stock_process.id as transection_unique_id,
          stock_process.id as transection_recipt_no,
          CONCAT("-",stock_process.grand_payment) as transection_recive, 
          SUM(stock_process.final_total-stock_process.grand_payment) as transection_paid
          FROM `stock_process`
          WHERE stock_process.user_id = "'.$user_id.'" AND 
          stock_process.status = 1 AND 
          stock_process.process_id = "'.$id.'" AND 
          stock_process.grand_total != "0.00" AND 
          stock_process.deleted_at IS NULL  
          group by stock_process.id';
      }

      if($master_type != "") {
          $addtional_query_new .= ' UNION SELECT
	expenses.id as transection_id, 
	expenses.date as transection_date,
	"EXPENSES" as transection_type,
	SUM(expenses.grand_payment-expenses.grand_total) as transection_amount,
	expenses_category.name as transection_remarks, 
	expenses.id as transection_unique_id, 
	expenses.bill_no as transection_recipt_no, 
	CONCAT("-",expenses.grand_payment) as transection_recive, 
    SUM(expenses.grand_total-expenses.grand_payment) as transection_paid 
	FROM `expenses` 
	LEFT JOIN expenses_category ON expenses.category = expenses_category.id
	WHERE expenses.user_id = "'.$user_id.'" AND 
	expenses.name = "'.$master_type.'_'.$id.'" AND 
	expenses.status = 1 AND 
	expenses.grand_total != "0.00" AND 
	expenses.deleted_at IS NULL
	group by expenses.id';
      }

    return 'select *
    from (

    SELECT
    '.$table.'.id as transection_id,
    '.$table.'.opening_asof as transection_date,
    IF('.$table.'.opening_type = "1", "PAYABLE", "RECIVABLE") as transection_type,
    IF('.$table.'.opening_type = "1", CONCAT("-",'.$table.'.opening_balance), '.$table.'.opening_balance) as transection_amount,
    CONCAT(IF('.$table.'.opening_type = "1", "Payable", "Receivable")," Opening Balance") as transection_remarks,
    "OPENING_BALANCE" as transection_unique_id,
    "" as transection_recipt_no,
    "" as transection_recive,
    "" as transection_paid
    FROM `'.$table.'`
    WHERE '.$table.'.user_id = "'.$user_id.'" AND '.$table.'.id = "'.$id.'"  AND '.$table.'.opening_asof IS NOT NULL  AND '.$table.'.opening_balance != "0.00" AND '.$table.'.deleted_at IS NULL

    UNION

    SELECT
    payment.id as transection_id,
    payment.transection_date as transection_date,
    IF(payment.type = "in", "PAYIN", "PAYOUT") as transection_type,
    IF(payment.type = "in", CONCAT("-",payment.amount) , ABS(payment.amount))  as transection_amount,
    payment.remarks as transection_remarks,
    payment.tid as transection_unique_id,
    payment.recipt_no as transection_recipt_no,
    "" as transection_recive,
    "" as transection_paid
    FROM `payment`
    WHERE payment.user_id = "'.$user_id.'" AND payment.master_type = "'.$master_type.'" AND payment.master_id = "'.$id.'" AND payment.status = 1 AND payment.deleted_at IS NULL
    
    '.$addtional_query_new.'
    

    ) a where 1 = 1 '.$additional_query.'
    ORDER BY transection_date DESC';
  }

}
