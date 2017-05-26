<?php
 header("Content-type: text/html; charset=utf-8");         


//query($key);

function query($key)
{
	//PC百度搜索
	$key  = urlencode($key);
	$url = 'http://www.baidu.com/s?word='.$key;
	$result = file_get_contents($url);
	/*$pattern = '#class="result.*?class="t".*?<a.*?href="(.*?)".*?>(.*?)</a>#si'; */
	$pattern = '#class="t".*?<a.*?href="(.*?)".*?>(.*?)</a>#six';
	preg_match_all($pattern,$result,$matches);

    $result = array();
    if(!empty($matches))
    {
        foreach($matches[0] as $key=>$val)
        {
            preg_match('#<a.*?href=".*?".*?>(.*?)</a>#six',$val,$match);
//            $result[$key]['name'] = strip_tags($match[0]);
            $result[$key]['name'] = mb_substr(strip_tags($match[0]), 0, 35, 'utf8');
            $result[$key]['url'] = $matches[1][$key];
        }
    }
    //print_r($result);die;

	//file_put_contents('./page.html', $matches[0][0]);
	//echo '<pre>';
	//print_r($matches);die;
	//var_dump(strip_tags($matches[0][0]));

	toxml($result);
}

function toxml($data)
{
    /*
     <items>
        <item autocomplete = "autocompletex" uid = "123321" arg = "argsx" >
        <title >title</title>
        <subtitle >subtitle</subtitle>
        <icon >icon</icon>
        </item>
    </items>
     */

    $dom = '<?xml version="1.0" encoding="utf-8"?>';
    $dom .= '<items>';
    foreach($data as $key=>$val)
    {
        $dom .= "<item autocomplete='autocompletex' uid='{$key}' arg='{$val['url']}'>";
        $dom .= "<title>{$val['name']}</title>";
        $dom .= "<subtitle></subtitle>";
        $dom .= "<icon></icon>";
        $dom .= '</item>';
    }
    $dom .= '</items>';

    echo $dom;
    exit;
}