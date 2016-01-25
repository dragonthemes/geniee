<?php
/**
 * Created by PhpStorm.
 * User: khangvu
 * Date: 1/23/2016
 * Time: 8:57 PM
 */
require 'simple_html_dom/simple_html_dom.php';
require 'my_curl.php';
require 'helper.php';

define('TARGET_URL', 'http://exam.geniee.info/web/login');
define('TARGET_URL_UPDATE', 'http://exam.geniee.info/web/update');
define('DATA_FILE', 'data.json');
define('DATA_UPDATE_FILE', 'data_update.json');

function get_login_data()
{
    $data = array(
        'username' => '',
        'password' => '',
        'token' => '',
        'login' => ''
    );

    $curl = myCURL::get_instance();
    $html = $curl->get(TARGET_URL);
    $dom = str_get_html($html);

    $account_element = $dom->find('.panel', 0)->find('.form-group', 3)->find('dd');

    $data['username'] = $account_element[0]->plaintext;
    $data['password'] = $account_element[1]->plaintext;
    $data['token'] = $dom->find('input[name=token]', 0)->value;

    return $data;
}


function get_source_after_login()
{
    $curl = myCURL::get_instance();
    $data = get_login_data();

    //do login and get respond source
    return $curl->post(TARGET_URL, build_data_string_from_array($data));
}

function get_source_after_update()
{
    $curl = myCURL::get_instance();
    return $curl->get(TARGET_URL_UPDATE);
}

function collect_data_to_file()
{
    $data = scrape_data(get_source_after_login());
    if (file_put_contents(DATA_FILE, json_encode($data)))
        echo '<p>Crawled data is put to file data.json </p>';
}

function collect_data_update_to_file()
{
    $data = json_decode(get_source_after_update(), true);
    usort($data, function ($a, $b) {
        return strtotime(str_replace('/', '-', $a["date"])) - strtotime(str_replace('/', '-', $b["date"]));
    });
    if (file_put_contents(DATA_UPDATE_FILE, json_encode($data)))
        echo '<p>Crawled data update is put to file data_update.json </p>';
}


collect_data_to_file();
collect_data_update_to_file();