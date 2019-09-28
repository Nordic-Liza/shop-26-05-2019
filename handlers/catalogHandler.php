<?php 
    include_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

    sleep(3);
    $conditions = '';
    if(!empty($_GET['price']) && isset($_GET['price'])) {
        
        $price=$_GET['price'];

        $price_array = explode('-', $price);

        if ( count($price_array) == 2 ) {
            $conditions = "WHERE `price` BETWEEN {$price_array[0]} AND {$price_array[1]}";
        } else {
            $conditions = "WHERE `price` >= {$price_array[0]}";
        }
    }


    // quantity
    // id --- catalog_id --- size_id --- quantity
    // 1           2            1           4
    // 2           2            2           15


    // explode();
    // implode();

    $count_products_on_page=4;

    if(!empty($_GET['numPage'])) {
        $now_page=$_GET['numPage'];
    }

    $start_row=($now_page-1)*$count_products_on_page;

    $count_sql="SELECT * FROM `catalog` $conditions";
    $result_count = mysqli_query($link, $count_sql);
    $count_pages=ceil(mysqli_num_rows($result_count)/$count_products_on_page);

    $query = "SELECT * FROM `catalog` $conditions  limit {$start_row}, {$count_products_on_page}";
    $result = mysqli_query($link, $query);

    $data=[
        'products'=> [],
        'pagination'=> [
            'countPages'=> $count_pages,
            'nowPage'=> $now_page
        ]
    ];

    while( $row = mysqli_fetch_assoc($result) ) {
        array_push($data['products'], $row);
    }

    echo json_encode($data);
?>