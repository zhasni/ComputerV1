#!/usr/bin/php
<?PHP

function myFilter($var)
{
  return ($var !== NULL && $var !== FALSE && $var !== '');
}

function clean($var)
{
if (preg_match('/x/', $var))
	$var = preg_replace('/x/', 'X', $var);

$tab = explode(' ', $var);
//print_r($tab);/////////////////////////////

//	$i = 0;
//	while ($tab[$i])
//	{
//		if ($tab[$i] === '+' && $tab[$i + 1] < 0)
//		{
//			$tab[$i] = '-';
//			$tab[$i + 1] = $tab[$i + 1] * -1;
//		}
//		else if ($tab[$i] === '-' && $tab[$i + 1] < 0)
//		{
//			$tab[$i] = '+';
//			$tab[$i + 1] = $tab[$i + 1] * -1;
//		}
//		$i = $i + 1;
//	}
//print_r($tab);///////////////////////////////
$tab = array_filter($tab, 'strlen');
$var = implode(' ', $tab);

 // echo $var."\n";////////////////////////////////////
return ($var);
}

function goLeft($var)
{
	$var = clean($var);
// echo $var."\n";/////////////////////////
	$tab = explode(' = ', $var);

	if (strcmp($tab[0], $tab[1]) === 0)
		exit("All real numbers are solution\n");
	// print_r($tab);

	$tab2 = explode(" ", $tab[1]);
	// print_r($tab2);

	$i = 0;

	while ($i < count($tab2)) 
	{
		if (is_numeric($tab2[$i]) && $tab2[$i] >= 0)
			//$tab[0] .=  " + ".($tab2[$i] * -1);
			$tab[0] .=  " ".($tab2[$i] * -1);
		else if (is_numeric($tab2[$i]) && $tab2[$i] <= 0)
			//$tab[0] .=  " + ".($tab2[$i] * -1);
			$tab[0] .=  " ".($tab2[$i] * -1);
		else if ($tab2[$i] === '+')
			$tab2[$i] == '+';
		else if ($tab2[$i] === '-')
		{
			$tab2[$i] = '+';
			$tab2[$i + 1] = $tab2[$i + 1] * -1;
		}
		else if ($tab2[$i] === '*')
			$tab[0] .=  " * ";
		else
			$tab[0] .=  $tab2[$i];
		$i = $i + 1;
	}
	$tab[1] = 0;
	$var = implode(' = ', $tab);

	$tab = NULL;
	$tab = explode(' ', $var);

// echo "$var\n";///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	 print_r($tab);/////////////////////////////////////////////////////

	$i = 0;
	while ($i < count($tab)) 
	{
		if ($tab[$i] === '-' && is_numeric($tab[$i + 1]) && $tab[$i + 1] < 0)
		{
			$tab[$i] = '+';
			$tab[$i + 1] = $tab[$i + 1] * -1;
		}
		else if ($tab[$i] === '+' && is_numeric($tab[$i + 1]) && $tab[$i + 1] < 0)
		{
			$tab[$i] = ' ';
		}
		$i = $i + 1;
	}
	$tab = array_filter($tab, 'strlen');
	$var = implode(' ', $tab);

	return ($var);
}

function getPoly($data)
{
	$calc = NULL;
	// print_r($data);///////////////////////////////////////////////////
	foreach ($data as $key) 
	{
		$tab3 = explode(' ', $key);
		$i = 0;
		while ($i < count($tab3))
		{
			if (is_numeric($tab3[$i]) || $tab3[$i] === '-')
			{
				// echo $tab3[$i];
				$calc .= " ".$tab3[$i];
			}
			$i = $i + 1;
		}
	}
	$data = explode(' ', $calc);
	$data = array_filter($data, 'strlen');
	$calc = implode(' ', $data);

	return ($calc);
}

function addVal($val)
{
	// echo "\n\n\nvalue to add 1 = ".$val."\n\n\n";////////////////////////////////////////////////
	$add = NULL;
	$i = 0;
	while ($val[$i])
	{
		if ($val[$i] === '-' && $val[$i + 1] === ' ' && ($val[$i + 2] >= '0' || $val[$i + 2] >= '9'))
		{
			$val[$i] = ' ';
			$val[$i + 1] = '-';
		}
		$i = $i + 1;
	}
	// echo "\n\n\nvalue to add 2 = ".$val."\n\n\n";///////////////////////////////////////////////
	$add_tab = explode(' ', $val);
	$add_tab = array_filter($add_tab, 'strlen');
	$val = implode(' ', $add_tab);
	// echo "\n\n\nvalue to add 3 = ".$val."\n\n\n";///////////////////////////////////////////////
	$add_tab = explode(' ', $val);
	// print_r($add_tab);////////////////////////////////////////////////////////////////////////////
	foreach ($add_tab as $key)
	{
//		 echo $key."\n";/////////////////////////////////////////////////////////////////////////////
		if (is_numeric($key))
			$add += $key;
	}
	return ($add);
}

function getData($nb, $str)
{
	// echo $nb."\n";//////////////////////////////////////////////////
	$pattern = "/[+|-]?\ ?[-|+]?[0-9]*[.]?[0-9]+\ \*\ X\^[+|-]?".$nb."\ /i";
	//$pattern = "/i[+|-]?\ ?[-|+]?[0-9]?[.]?[0-9]+\ +\*\ +X\^[-|+]?".$nb."[.]?[0-9]*\ +/i"; 
	$poly = "X^".$nb;
	// echo $pattern."\n";//////////////////////////////////////////////////////
	// echo $poly."\n";/////////////////////////////////////////////////////////
	preg_match_all($pattern, $str, $data_tab);
	// echo count($data_tab);///////////////////////////////////////////////////
	// print_r($data_tab);//////////////////////////////////////////////////////
	$val = getPoly($data_tab[0]);
	$reduced = addVal($val);
	if (empty($reduced) == FALSE)
{
//		echo ($reduced).PHP_EOL;//////////////
//		if ($reduced < 0)
//			$reduced = "- ".$reduced * -1;
//		else			
//			$reduced = "+ ".$reduced;
		return array($str, ($reduced." * ".$poly));
}
	else
		return array($str, NULL);
}

function getMaxPoly($var)
{
	$tmp = 0;

	preg_match_all("/\^([+|-]?[0-9]+[.|,]?[0-9]*)/", $var, $tab);
//print_r($tab);///////////////////////////
	foreach ($tab[1] as $elem)
	{
//	echo $elem.PHP_EOL;
//	var_dump(is_int($elem));
		if ($elem < 0)
			exit("Format ERROR : power < 0 not allowed !\n");
		if (preg_match('/[,|.]/', $elem))
			exit("Format ERROR : float power not allowed !\n");
		if ($elem >= $tmp)
			$tmp = $elem;
	}
	return ($tmp);

}

function getDelta($var)
{
	// echo $var.PHP_EOL;///////////////////////////////////////////////////////
	$a = 0;
	$b = 0;
	$c = 0;
	$i = 0;
	while ($i < 3)
	{
		$j = 0;
		$flag = 0;
		$pattern = "/[+|-]?\ ?[-|+]?[0-9]*[.]?[0-9]+\ \*\ X\^[+|-]?".$i."\ /i";
		//$pattern = "/i[+|-]?\ ?[-|+]?[0-9]?[.]?[0-9]+\ +\*\ +X\^[-|+]?".$i."[.]?[0-9]*\ +/i"; 
		preg_match_all($pattern, $var, $delta_tab);
		// print_r($delta_tab);//////////////////////////////////////////////////////////
		$poly_tab = explode(' ', $delta_tab[0][0]);
		// print_r($poly_tab);//////////////////////////////////////////////////////////
		while ($poly_tab[$j])
		{
			// echo $poly_tab[$j].PHP_EOL;
			// var_dump(is_numeric($poly_tab[$j])).PHP_EOL;//////////////////////////////////
			if ($poly_tab[$j] === '-')
				$flag = 1;
			if ($flag === 1 && is_numeric($poly_tab[$j]) === TRUE)
			{
				if ($i === 0)
					$c = $poly_tab[$j] * -1;
				else if ($i === 1)
					$b = $poly_tab[$j] * -1;
				else if ($i === 2)
					$a = $poly_tab[$j] * -1;
			}
			else if ($flag === 0 && is_numeric($poly_tab[$j]) === TRUE)
			{
				// echo "flag = 0\n";
				if ($i === 0)
					$c = $poly_tab[$j];
				else if ($i === 1)
					$b = $poly_tab[$j];
				else if ($i === 2)
					$a = $poly_tab[$j];
			}
			$j = $j + 1;
		}
		$i = $i + 1;
	}
	// echo $a.PHP_EOL.$b.PHP_EOL.$c.PHP_EOL;////////////////////////////////////////////////////////
	$delta = ($b * $b) - (4 * ($a * $c));
	// echo $delta.PHP_EOL;////////////////////////////////////////////////////////////////
	return array($delta, $a, $b, $c);
}

function getSoluce($str)
{
//	 echo $str."\n";///////////////////////////////////////////////////////////

	$pattern = '/[+|-]?\ ?[-|+]?[0-9]*[.]?[0-9]+\ \*\ X\^[+|-]?0\ /i';
	//$pattern = '/i[+|-]?\ ?[-|+]?[0-9]?[.]?[0-9]+\ +\*\ +X\^[-|+]?0[.]?[0-9]*\ +/i'; 
	if (preg_match($pattern, $str))
	{
		preg_match_all($pattern, $str, $sol_tab);
	//	 print_r($sol_tab);//////////////////////////////////////////////////////////
		$str = preg_replace($pattern, '', $str);
	}
	$tab_right = explode(' ', $sol_tab[0][0]);
	// print_r($tab_right);//////////////////////////////////////////////////////////
	$tab_resolve = explode(" = ", $str);
	// print_r($tab_resolve);//////////////////////////////////////////////////////
	$i = 0;
	$flag = 0;
	foreach ($tab_right as $key) 
	{
		if (is_numeric($key) === TRUE)
			$result = $tab_right[$i] * -1;
	}
	/* OR */
	// while ($tab_right[$i])
	// {
	// 	echo $tab_right[$i].PHP_EOL;//////////////////////////////////////////////////
	// 	if ($tab_right[$i] === '-')
	// 		$flag = 1;
	// 	if ($flag === 1 && is_numeric($tab_right[$i]) === TRUE)
	// 	{
	// 		$tab_resolve[1] = $tab_right[$i];
	// 	}
	// 	else if ($flag === 0 && is_numeric($tab_right[$i]) === TRUE)
	// 	{
	// 		$tab_resolve[1] = $tab_right[$i] * -1;
	// 	}
	// 	$i = $i + 1;
	// }

	// $str = implode(" = ", $tab_resolve);
	if (preg_match('/\*/', $tab_resolve[0]))
		$result .= " /";
	$tab_left = explode(" ", $tab_resolve[0]);
//	 print_r($tab_left);//////////////////////////////////////////////////////
	foreach ($tab_left as $key) 
	{	
		// echo $key.PHP_EOL;////////////////////////////////////////////////////////////
		if (is_numeric($key) === TRUE)
			$result .= " ".$key * -1;
	}
	$tab_result = explode(' ', $result);
	// print_r($tab_result);//////////////////////////////////////////////////////
	$result = ($tab_result[0] / $tab_result[2] * -1);
	// echo $tab_result[0]."\n".$tab_result[1]."\n".($tab_result[2] * -1)."\n";///////////////////////////////////////////////////////////
	return array($result, $tab_result[0], $tab_result[1], ($tab_result[2] * -1));

}

function affReduce($var)
{
//echo $var.PHP_EOL;/////////////////////////////
	$tab = explode(' ', $var);
//print_r($tab);///////////////////////////////
	$i = 0;
	while ($tab[$i])
	{
		if ($tab[$i] === '+' && $tab[$i + 1] < 0)
		{
			$tab[$i] = '-';
			$tab[$i + 1] = $tab[$i + 1] * -1;
		}
		else if ($tab[$i] === '-' && $tab[$i + 1] < 0)
		{
			$tab[$i] = '+';
			$tab[$i + 1] = $tab[$i + 1] * -1;
		}
		$i = $i + 1;
	}
//print_r($tab);///////////////////////////////
	$var = implode(' ', $tab);
echo $var.PHP_EOL;/////////////////////////////
}

if ($argc != 2)
	exit("ERROR : too many or too few arguments\n");

$simplification = goLeft($argv[1]);

$max = getMaxPoly($simplification);

// echo $max."\n";/////////////////////////////////////////////////////////
// [+|-]?\ ?[-|+]?[0-9]\ \*\ X\^[0]
// -1 * X^0 - 2 * X^1 + -1 * X^0 + -2 * X^1 = 0

$reduce = NULL;
$i = 0;
while ($i < $max + 1)
{
	list($simplification, $ret) = getData($i, $simplification);
	// echo "\nvar_dump = ";////////////////////////////////////////////////////
	// var_dump($reduce);//////////////////////////////////////////////////////
	if (empty($reduce) === FALSE && empty($ret) === FALSE)
	{
		// echo "ret =<".$ret.">\n";//////////////////////////////////////////////
		$reduce .= " + ".$ret;
	}
	else
		$reduce .= $ret;
	// echo $simplification."\n";
	// echo $ret."\n";
// 	// echo $data."\n";///////////////////////////////////////////////////////////////////
	$i = $i + 1;
}
$reduce .= " = 0";
if (strcmp($reduce, " = 0") === 0)
	exit("Format ERROR : wrong equation syntax\n");
if ($max > 0)
{
//	echo "Reduce form: ".$reduce."\n";
	echo "Reduce form: ";
	affReduce($reduce);
	$max = getMaxPoly($reduce);
	echo "Polynomial degree: ".$max."\n";
}
if ($max >= 3)
	echo "The polynomial degree (".$max.") is stricly greater than 2, I can't solve.\nYou have to wait for ComputorVX :-D !\n";
else if ($max == 2)
{	
	List($Discriminant, $a, $b, $c) = getDelta($reduce);
//echo $Discriminant.PHP_EOL;///////////////////////
	if ($Discriminant > 0)
	{
		echo "Discriminant is strictly Positive (".$Discriminant."), the two solutions are :" . PHP_EOL;
		// x1 = (-b-√Δ)/2a et x2= (-b+√Δ)/2a
		echo "(".($b * -1)." - sqrt(".($Discriminant).") / ".(2 * $a).PHP_EOL;
		echo "(".($b * -1)." + sqrt(".($Discriminant).") / ".(2 * $a).PHP_EOL;
		
		echo "OR\n";

		echo ($b * -1)." / ".(2 * $a)." - ".((sqrt($Discriminant)) / (2 * $a)).PHP_EOL;
		echo ($b * -1)." / ".(2 * $a)." + ".((sqrt($Discriminant)) / (2 * $a)).PHP_EOL;
		 
		echo "OR\n";

		echo (($b * -1) - (sqrt($Discriminant))) / (2 * $a).PHP_EOL;
		echo ((($b * -1) + (sqrt($Discriminant))) / (2 * $a)).PHP_EOL;
	}
	else if ($Discriminant < 0)
{
		echo "Discriminant is strictly Negative (".$Discriminant."), the two complex conjugates solutions are :" . PHP_EOL;
		// x1 = (-b-i√Δ)/2a et x2= (-b+i√Δ)/2a
		
		echo "(".($b * -1)." - i * sqrt(".($Discriminant * -1).") / ".(2 * $a).PHP_EOL;
		echo "(".($b * -1)." + i * sqrt(".($Discriminant * -1).") / ".(2 * $a).PHP_EOL;
		
		echo "OR\n";

		echo ($b * -1)." / ".(2 * $a)." - i * ".((sqrt($Discriminant * -1)) / (2 * $a)).PHP_EOL;
		echo ($b * -1)." / ".(2 * $a)." + i * ".((sqrt($Discriminant * -1)) / (2 * $a)).PHP_EOL;
		 
		echo "OR\n";

		echo ($b * -1) / (2 * $a)." - i * ".((sqrt($Discriminant * -1)) / (2 * $a)).PHP_EOL;
		echo ($b * -1) / (2 * $a)." + i * ".((sqrt($Discriminant * -1)) / (2 * $a)).PHP_EOL;
		
}	else
	{
		echo "Discriminant is strictly Equal to 0 (null), the only solution is :" . PHP_EOL;
		echo ($b * -1)." / ".(2 * $a).PHP_EOL;
		 
		echo "OR\n";

		echo ($b * -1) / (2 * $a).PHP_EOL;
	}
	// echo $a.PHP_EOL.$b.PHP_EOL.$c.PHP_EOL;////////////////////////////////////////////////////////
	// echo $Discriminant.PHP_EOL;////////////////////////////////////////////////////////////////

}
else if ($max == 1)
{
	echo "the solution is :\n";
	List($soluce, $a, $mid, $b) = getSoluce($reduce);
	echo $a." ".$mid." ".$b.PHP_EOL;
	echo "OR\n";
	echo $soluce.PHP_EOL;
}
else
	echo "There are no solutions\n";
// $tab = explode(' ', $simplification);
// echo count($tab);/////////////////////////////////////////////////////////////////

?>
