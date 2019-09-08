#Simple Documentation
###List of methods
#### 1. Admin section
```
 ---------------------------------- -------- ------------------------------- 
  Name                               Method   Path                           
 ---------------------------------- -------- ------------------------------- 
  admin-article-post                 POST     /v1/admin/article              
  admin-article-put                  PUT      /v1/admin/article              
  admin-article-delete               DELETE   /v1/admin/article   
           
  admin-item-list-post               POST     /v1/admin/item/list            
  admin-item-list-put                PUT      /v1/admin/item/list            
  admin-item-list-delete             DELETE   /v1/admin/item/list   
         
  admin-mod-post                     POST     /v1/admin/mod                  
  admin-mod-put                      PUT      /v1/admin/mod                  
  admin-mod-delete                   DELETE   /v1/admin/mod       
           
  admin-item-post                    POST     /v1/admin/item                 
  admin-item-put                     PUT      /v1/admin/item                 
  admin-item-delete                  DELETE   /v1/admin/item       
          
  admin-regulation-category-post     POST     /v1/admin/regulation/category  
  admin-regulation-category-put      PUT      /v1/admin/regulation/category  
  admin-regulation-category-delete   DELETE   /v1/admin/regulation/category
  
  admin-regulation-post              POST     /v1/admin/regulation           
  admin-regulation-put               PUT      /v1/admin/regulation           
  admin-regulation-delete            DELETE   /v1/admin/regulation        
   
  admin-contact-put                  PUT      /v1/admin/contact              
  admin-contact-delete               DELETE   /v1/admin/contact    
```
#### 1. User section
```
 ---------------------------------- -------- ------------------------------- 
  Name                               Method   Path                           
 ---------------------------------- -------- -------------------------------          
  user-register                      POST     /v1/user/register              
  user-login                         POST     /v1/user/login                 
  user-login-launcher                POST     /v1/user/launcher/login        
  user-reset-password                POST     /v1/user/reset                 
  user-reset-from-token              POST     /v1/user/reset/{token}         
  user-item-list-execute             POST     /v1/user/item/list                 
  user-item-execute                  POST     /v1/user/item   
  user-item-list                     GET      /v1/user/items   
  user-cash                          GET      /v1/user/cash                       
  user-update                        PUT      /v1/user 
         
  payment-pypal-confirmation         POST     /v1/payment/paypal             
  payment-sms-confirmation           POST     /v1/payment/sms    
            
  shop-buy-items                     POST     /v1/shop/buy   
  shop-get-items                     GET      /v1/shop/list                  

  player-avatar                      GET      /v1/player/avatar              
  player-list                        GET      /v1/player/list    

  contact                            POST     /v1/contact          
  contact-ticket                     GET      /v1/contact/{ticket}           
  
  article                            GET      /v1/article            
 ---------------------------------- -------- -------------------------------  
```
