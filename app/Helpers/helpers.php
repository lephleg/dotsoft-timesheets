<?php

/**
 * Returns a dates timestamp boundaries (e.g. 2000-01-01 00:00:00 and 2000-01-01 23:59:59)
 *
 * @param null $timestamp
 *
 * @return array
 */

function getDayBoundaries($timestamp = null)
{

    if ( ! $timestamp) {
        $timestamp = date("Y-m-d H:i:s");
    }

    $start = (new DateTime($timestamp))->format('Y-m-d 00:00:00');
    $end   = (new DateTime($timestamp))->format('Y-m-d 23:59:59');

    return compact('start', 'end');
}

/**
 * Return color hex values for the VIBGYOR rainbow scheme
 *
 * @return array
 */
function getRainbowColorScheme()
{
    $vibgyor = [
        'violet' => '#8D008D',
        'indigo' => '#2F005E',
        'blue'   => '#0066FF',
        'green'  => '#00BC38',
        'yellow' => '#EBBC2F',
        'orange' => '#FF9900',
        'red'    => '#CC0000'
    ];

    return $vibgyor;
}

/**
 * Executes comparison between two DateInterval objects
 *
 * @param $interval1
 * @param $operator
 * @param $interval2
 *
 * @return bool|null
 */
function compareDateInterval($interval1, $operator, $interval2)
{
    $interval1_str = $interval1->format("%Y%M%D%H%I%S");
    $interval2_str = $interval2->format("%Y%M%D%H%I%S");
    switch ($operator) {
        case "<":
            return $interval1 < $interval2;
        case ">":
            return $interval1 > $interval2;
        case "==" :
            return $interval1 == $interval2;
        default:
            return null;
    }
}

/**
 * Adds two DateInterval objects
 *
 * @param $interval1
 * @param $interval2
 *
 * @return DateInterval
 */
function addDateInterval($interval1, $interval2)
{
    //variables
    $new_value = [];
    $carry_val = [
        's' => ['value' => 60, 'carry_to' => 'i'],
        'i' => ['value' => 60, 'carry_to' => 'h'],
        'h' => ['value' => 24, 'carry_to' => 'd'],
        'm' => ['value' => 12, 'carry_to' => 'y']
    ];

    //operator selection
    $operator = ($interval1->invert == $interval2->invert) ? '+' : '-';

    //Set Invert
    if ($operator == '-') {
        $new_value['invert'] = compareDateInterval($interval1, ">",
            $interval2) ? $interval1->invert : $interval2->invert;
    } else {
        $new_value['invert'] = $interval1->invert;
    }

    //Evaluate
    foreach (str_split("ymdhis") as $property) {
        $expression           = 'return ' . $interval1->$property . ' ' . $operator . ' ' . $interval2->$property . ';';
        $new_value[$property] = eval($expression);
        $new_value[$property] = ($new_value[$property] > 0) ? $new_value[$property] : -$new_value[$property];
    }

    //carry up
    foreach ($carry_val as $property => $option) {
        if ($new_value[$property] >= $option['value']) {
            //Modulus
            $new_value[$property] = $new_value[$property] % $option['value'];
            //carry over
            $new_value[$option['carry_to']]++;
        }
    }

    $nv             = $new_value;
    $result         = new DateInterval("P$nv[y]Y$nv[m]M$nv[d]DT$nv[h]H$nv[i]M$nv[s]S");
    $result->invert = $new_value['invert'];

    return $result;
}

