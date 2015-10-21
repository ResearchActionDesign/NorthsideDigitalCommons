<?php function exhibit_get_images() {
$exhibits=get_records('Exhibit' , array ('featured'=>true));
foreach (loop('exhibit',$exhibits) as $exhibit){
$items=get_records('item', array('hasImage'=>true, 'exhibit'=>$exhibit),$num=2);
if(count($items)>=$num){
set_loop_records('items', $items);
if (has_loop_records('items')){
foreach (loop('items') as $item){
echo link_to_item(item_image('square_thumbnail'));

}
}
}
}
}
?><!-- end exhibit-items -->

<?php function collection_get_images() {
$collections=get_records('Collection' , array ('featured'=>true));
foreach (loop('collection',$collections) as $collection){
$items=get_records('item', array('hasImage'=>true, 'collection'=>$collection),$num=1);
if(count($items)>=$num){
set_loop_records('items', $items);
if (has_loop_records('items')){
foreach (loop('items') as $item){
echo link_to_item(item_image('square_thumbnail'));
}
}
}
}
}
?>