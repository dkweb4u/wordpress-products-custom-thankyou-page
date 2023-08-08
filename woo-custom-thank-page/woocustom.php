<?php
/*
/*
 * Plugin Name:       Woo Custom ThankYou
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Redirect users to a custom WooCommerce thank you page based on a product or Variation of bought in the order.
 * Version:           1.0
 * Author:            Dinesh Kumar
 * Author URI:        https://ddk.netlify.app/
 * License:           GPL v2 or later
*/


function custom_dashboard_plugin_menu() {
    add_menu_page(
        'Woo Custom Thankyou Page',
        'Woo Custom Thankyou Page',
        'manage_options',
        'woo-custom-thankyou-page',
        'woo_custom_thankyou_page'
    );
}
add_action('admin_menu', 'custom_dashboard_plugin_menu');
function woo_custom_thankyou_page() {
?>
    <div class="wrap">
        <h2>Custom Dashboard</h2>
    
        <hr>

        <p> Redirect users to a custom WooCommerce thank you page based on a product or Variation of bought in the order. Start to Create</p>
        <p>Product Id or Variation Id  any one or both also Use</p>
        <p>After Order suceesfull then only redirect</p>

      <button class="createbtn button button-primary">Create</button>

      <hr>

      <form action="" method="post" style="max-width: 1000px; overflow: scroll; width: 100%; max-height: 80vh;">
      <table style="width: 90%; text-align: center;border-spacing: 10px;">
        <thead>
          <tr style="font-size: 14px !important;">
            <th>S.No</th>
            <th>Product Id</th>
            <th>Variation Id</th>
            <th>Redirect Link</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody class="current-data">

        <?php 
        

        // directory path
        $plugin_dir = plugin_dir_path(__FILE__);

        $file_name = 'data.json';

        $file_path = $plugin_dir . $file_name;
        // directory path




        $jsondata = file_get_contents($file_path);
                
        $jsondata = json_decode($jsondata);
        
      if(!empty($jsondata)){
        for ($i=0; $i < count($jsondata); $i++) { 
        
            echo '<tr class="current-data-row"><td>' . $i+1 . '</td> <td><input type="number" name="plugin_id[]" value="'. $jsondata[$i][0] .'"></td> <td> <input type="number" name="variation_id[]" value="'. $jsondata[$i][1] .'"> </td><td> <input type="text" name="link[]" value="'. $jsondata[$i][2] .'"> </td><td><div class="removebtn button button-primary">Remove</div></td>   </tr>';
           
           }
      }
        
        ?>


</tbody>
      </table>
      <hr>
      <button class="button button-primary" type="submit" name="btnsave">Save</button>
    </form>


     

    </div>


    <script>
      let tablebody = document.querySelector(".current-data");


      let create = document.querySelector(".createbtn");

      create.addEventListener("click", () => {

        let removebtn = document.querySelectorAll(".removebtn");

        let tablerow = document.createElement('tr');
 
       tablerow.classList.add('current-data-row');

       tablerow.innerHTML = '<tr class="current-data-row"><td>'+ (removebtn.length + 1) +'</td><td> <input type="number" name="plugin_id[]" > </td><td> <input type="number" name="variation_id[]"> </td><td> <input type="text" name="link[]"> </td>    <td><div class="removebtn button button-primary">Remove</div></td></tr> ';
       


       tablebody.append(tablerow);
        
        removebtnfun();
       
      });

      removebtnfun();
function removebtnfun(){
    let removebtn = document.querySelectorAll(".removebtn");

    let currentRow = document.querySelectorAll(".current-data-row");


        if (removebtn) {
          removebtn.forEach((item, i) => {
            item.addEventListener("click", () => {
              currentRow[i].remove();
            });
          });
        }
}
   
    </script>



    
<?php
}



if(isset($_POST['btnsave'])){


    $plugin_dir = plugin_dir_path(__FILE__);

    $file_name = 'data.json';

    $file_path = $plugin_dir . $file_name;


    if(!empty($_POST['plugin_id'])){
 
     $total = count($_POST['plugin_id']);
 
     $jsonarray = array();
 
 
 
     for($i =0; $i< $total; $i++){
 
 
         if($_POST['link'][$i] !=" " && $_POST['link'][$i] != null && !empty($_POST['link'][$i]) && (!empty($_POST['plugin_id'][$i]) || !empty($_POST['variation_id'][$i]))){
 
 
             $current = array($_POST['plugin_id'][$i],$_POST['variation_id'][$i],$_POST['link'][$i]);
 
 
             array_push($jsonarray, $current);
 
         }     
        
     }


   
     if(file_put_contents( $file_path ,json_encode($jsonarray))){

         
         echo '<script>alert("Saved Successfully");location.replace(document.referrer)</script>';
     }
 
     else{
         echo '<script>alert("Something Wrong");location.replace(document.referrer)</script>';
     }
 
    }
 
 
    else{

     file_put_contents( $file_path ,"");
     
     echo '<script>alert("No Data");</script>';
    }
   
 }

// =============================================================================================================================


add_action( 'template_redirect', 'ecommercehints_product_dependant_thank_you_page' );
 
function ecommercehints_product_dependant_thank_you_page(){
 
   if( !is_wc_endpoint_url( 'order-received' ) || empty( $_GET['key'] ) ) {
      return;
   }
 
   $order_id = wc_get_order_id_by_order_key( $_GET['key'] );
   $order = wc_get_order( $order_id );
// ============================================================================
   $plugin_dir = plugin_dir_path(__FILE__);

   $file_name = 'data.json';

   $file_path = $plugin_dir . $file_name;

// =============================================================================

 
   foreach( $order->get_items() as $item ) {

    $jsondata = file_get_contents($file_path);
                
    $jsondata = json_decode($jsondata);


    if(!empty($jsondata)){

        for($i=0; $i<count($jsondata); $i++){


            if( $item['product_id'] == $jsondata[$i][0] || $item['variation_id'] == $jsondata[$i][1] ) { // product id here
                wp_redirect( $jsondata[$i][2] ); // your custom thank you page url here
                exit;
             }


        }


      


    }



     
   }
 
}












 
 ?>

