<div class="single-product shop-quick-view-ajax clearfix">
                    <?php  foreach ($shop_item as $producto ){ 
                    $Base64Img = base64_encode($producto->file_data);
                    $sm = $this->config->item("thousand_separator");
                    $sd = $this->config->item("decimal_separator");
                    $symbol = $this->config->item("currency_symbol");
                    $cost_price = number_format($producto->cost_price, 2, $sm, $sd);
                    $description = $producto->description;
                    
                    
                    if(empty($Base64Img)){
                    	$src=base_url("img/no-photo.jpg");
                    
                    }else {
                    	$src="data:image/jpeg;base64,". $Base64Img;
                    
                    }
                    ?>
                   <div class="ajax-modal-title">
                       <h2><?= $producto->name ?></h2>
                   </div>

                   <div class="product modal-padding clearfix">

                       <div class="col_half nobottommargin">
                           <div class="product-image">
                               <div class="fslider" data-pagi="false">
                                   <div class="flexslider">
                                       <div class="slider-wrap">
                                           <div class="slide"><a href="#" title="Pink Printed Dress - Front View"><img src="<?php echo  $src; ?>" alt="Pink Printed Dress"></a></div>
                                       </div>
                                   </div>
                               </div>
                               <div class="sale-flash">Sale!</div>
                           </div>
                       </div>
                       <div class="col_half nobottommargin col_last product-desc">
                           <div class="product-price"><del><?= $cost_price ?></del> <ins><?= $cost_price ?></ins></div>
                           <div class="product-rating">
                               <i class="icon-star3"></i>
                               <i class="icon-star3"></i>
                               <i class="icon-star3"></i>
                               <i class="icon-star-half-full"></i>
                               <i class="icon-star-empty"></i>
                           </div>
                           <div class="clear"></div>
                           <div class="line"></div>

                           <!-- Product Single - Quantity & Cart Button
                           ============================================= -->
                           <?php echo form_open('#',['class'=>'cart nobottommargin clearfix', 'method'=>'POST', 'enctype'=>'multipart/form-data']);?>
                               <div class="quantity clearfix">
                                   <input type="button" value="-" class="minus">
                                   <input type="text" step="1" min="1"  name="quantity" value="1" title="Qty" class="qty" size="4" />
                                   <input type="button" value="+" class="plus">
                               </div>
                               <button type="submit" class="add-to-cart button nomargin">Add to cart</button>
                           </form><!-- Product Single - Quantity & Cart Button End -->

                           <div class="clear"></div>
                           <div class="line"></div>
                           <p><?= $description ?></p>
                           <ul class="iconlist">
                               <li><i class="icon-caret-right"></i> Dynamic Color Options</li>
                               <li><i class="icon-caret-right"></i> Lots of Size Options</li>
                               <li><i class="icon-caret-right"></i> 30-Day Return Policy</li>
                           </ul>
                           <div class="panel panel-default product-meta nobottommargin">
                               <div class="panel-body">
                                   <span itemprop="productID" class="sku_wrapper">SKU: <span class="sku">8465415</span></span>
                                   <span class="posted_in">Category: <a href="http://localhost/offbeat/wp/product-category/shoes/" rel="tag">Shoes</a>.</span>
                                   <span class="tagged_as">Tags: <a href="http://dante.swiftideas.net/product-tag/barena/" rel="tag">Barena</a>, <a href="http://dante.swiftideas.net/product-tag/blazers/" rel="tag">Blazers</a>, <a href="http://dante.swiftideas.net/product-tag/tailoring/" rel="tag">Tailoring</a>, <a href="http://dante.swiftideas.net/product-tag/unconstructed/" rel="tag">Unconstructed</a>.</span>
                               </div>
                           </div>
                       </div>

                   </div>
                   <?php } ?>

               </div>