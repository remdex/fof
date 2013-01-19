<?php


$list = erLhAbstractModelAuctionLocation::getList(array('filter' => array('auction_id' => $Params['user_parameters']['auction_id']),'limit' => 1000));

$result = '<select name="AuctionLocationID" class="select-input">
<option>'.erTranslationClassLhTranslation::getInstance()->getTranslation('cargo/cargo','Choose an option').'</option>';

foreach ($list as $item)
{
    $selected = $Params['user_parameters']['selected_location'] == $item->id ? 'selected="selected"' : '';
    $result .= '<option value="'.$item->id.'" '.$selected.' >'.$item->name.'</option>';
}

$result .= '</select>';

echo json_encode(array('result' => $result));
exit;