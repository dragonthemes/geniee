<?php

function build_data_string_from_array($data)
{
    $data_string = '';
    foreach ($data as $params => $value) {
        $data_string .= "&$params=$value";
    }

    return $data_string;
}

function scrape_data($html)
{
    $data = array();

    $dom = str_get_html($html);

    $rows = $dom->find('tr.data');
    if ($rows) {
        foreach ($rows as $key => $row) {
            $columns = $row->find('td');
            $data[$key]['partner'] = $columns[0]->plaintext;
            $data[$key]['placement_id'] = $columns[1]->plaintext;
            $data[$key]['impression'] = $columns[2]->plaintext;
            $data[$key]['click'] = $columns[3]->plaintext;
            $data[$key]['revenue'] = $columns[4]->plaintext;
            $data[$key]['date'] = $columns[5]->plaintext;
        }
    }

    usort($data, function ($a, $b) {
        return strtotime(str_replace('/', '-', $a["date"])) - strtotime(str_replace('/', '-', $b["date"]));
    });

    return $data;
}
