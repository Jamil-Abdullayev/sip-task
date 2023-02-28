<h1>2 Task output</h1>
<?php
    $list = [[[1,2],3],[4],[5,6],[7,[8,9]]];
    //i try to write recursion for this, but without loop this is unreal, and after i remember this perfect function :D
array_walk_recursive($list, function($v) use (&$flat){ $flat[] = $v; });

echo '<pre>';
print_r($flat);
echo '</pre>';
?>
