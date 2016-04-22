<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
</div> <!-- //bx_content_section-->
<?if ($wizTemplateId == "eshop_adapt_vertical"):?>
    <div class="bx_sidebar">
        <?$APPLICATION->IncludeComponent(
                "bitrix:menu", 
                "catalog_vertical", 
                array(
                    "ROOT_MENU_TYPE" => "left",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "36000000",
                    "MENU_CACHE_USE_GROUPS" => "N",
                    "CACHE_SELECTED_ITEMS" => "N",
                    "MENU_THEME" => "site",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "MAX_LEVEL" => "3",
                    "CHILD_MENU_TYPE" => "left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N"
                ),
                false
            );?>
    </div> 
    <div class="bx_sidebar" style="float: top">
        <?$APPLICATION->IncludeComponent("bitrix:catalog.viewed.products", ".default", array(
                "LINE_ELEMENT_COUNT" => "3",
                "TEMPLATE_THEME" => "red",
                "DETAIL_URL" => "",
                "BASKET_URL" => "/personal/cart/",
                "ACTION_VARIABLE" => "action",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "SHOW_OLD_PRICE" => "N",
                "SHOW_DISCOUNT_PERCENT" => "Y",
                "PRICE_CODE" => "",
                "SHOW_PRICE_COUNT" => "1",
                "PRODUCT_SUBSCRIPTION" => "N",
                "PRICE_VAT_INCLUDE" => "Y",
                "USE_PRODUCT_QUANTITY" => "N",
                "SHOW_NAME" => "Y",
                "SHOW_IMAGE" => "Y",
                "MESS_BTN_BUY" => "Купить",
                "MESS_BTN_DETAIL" => "Подробнее",
                "MESS_BTN_SUBSCRIBE" => "Подписаться",
                "PAGE_ELEMENT_COUNT" => "3",
                "SHOW_FROM_SECTION" => "N",
                "SHOW_PRODUCTS_2" => "N",
                "ADDITIONAL_PICT_PROP_2" => "",
                "LABEL_PROP_2" => "-",
                "ADDITIONAL_PICT_PROP_3" => "MORE_PHOTO",
                "OFFER_TREE_PROPS_3" => array(
                    0 => "-",
                ),
                "SHOW_PRODUCTS_9" => "Y",
                "ADDITIONAL_PICT_PROP_9" => "MORE_PHOTO",
                "LABEL_PROP_9" => "-",
                "ADDITIONAL_PICT_PROP_10" => "MORE_PHOTO",
                "OFFER_TREE_PROPS_10" => array(
                    0 => "-",
                ),
                "SHOW_PRODUCTS_11" => "Y",
                "ADDITIONAL_PICT_PROP_11" => "",
                "LABEL_PROP_11" => "-",
                "HIDE_NOT_AVAILABLE" => "N",
                "CONVERT_CURRENCY" => "N",
                "IBLOCK_ID" => "",
                "SECTION_ID" => "",
                "SECTION_CODE" => "",
                "PROPERTY_CODE_2" => array(
                    0 => "",
                    1 => "",
                ),
                "CART_PROPERTIES_2" => array(
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE_3" => array(
                    0 => "",
                    1 => "",
                ),
                "CART_PROPERTIES_3" => array(
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE_9" => array(
                    0 => "",
                    1 => "",
                ),
                "CART_PROPERTIES_9" => array(
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE_10" => array(
                    0 => "",
                    1 => "",
                ),
                "CART_PROPERTIES_10" => array(
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE_11" => array(
                    0 => "",
                    1 => "",
                ),
                "CART_PROPERTIES_11" => array(
                    0 => "",
                    1 => "",
                )
                ),
                false,
                array(
                    "ACTIVE_COMPONENT" => "N"
                )
            );?> 
    </div>
    <div style="clear: both;"></div>
    <?endif?>
</div> <!-- //worakarea_wrap_container workarea-->
</div> <!-- //workarea_wrap-->

<div class="bottom_wrap">
    <div class="bottom_wrap_container">
        <div class="bottom_container_one">
            <div class="bx_inc_about_footer">
                <h4><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_name.php"), false);?></h4>
                <p><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_about.php"), false);?></p>
                <br/><br/>
                <a href="<?=SITE_DIR?>about/"><?=GetMessage("FOOTER_COMPANY_ABOUT")?></a>
            </div>
        </div>
        <div class="bottom_container_two">
            <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR."include/news.php",
                        "AREA_FILE_RECURSIVE" => "N",
                        "EDIT_MODE" => "html",
                    ),
                    false,
                    Array('HIDE_ICONS' => 'Y')
                );?>
        </div>
        <div class="bottom_container_tre">
            <div class="bx_inc_social_footer">
                <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/facebook_plugin.php"), false);?>
            </div>
        </div>
    </div>
</div>  <!-- //bottom_wrap -->

<div class="footer_wrap">
    <div class="footer_wrap_container">
        <div class="footer_container_one">
            <div class="bx_inc_catalog_footer">
                <h3><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/catalog_title.php"), false);?></h3>
                <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
                        "ROOT_MENU_TYPE" => "left",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MAX_LEVEL" => "1",
                        "USE_EXT" => "Y",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                        ),
                        false
                    );?>
            </div>
        </div>
        <div class="footer_container_two">
            <div class="bx_inc_menu_footer">
                <h3><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_title.php"), false);?></h3>
                <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
                        "ROOT_MENU_TYPE" => "bottom",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        ),
                        false
                    );?>
            </div>
        </div>
        <div class="footer_container_tre">
            <div class="footer_social_icon">
                <?
                    $facebookLink = $APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/socnet_facebook.php");
                    $twitterLink = $APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/socnet_twitter.php");
                    $googlePlusLink = $APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/socnet_google.php");
                    $vkLink = $APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/socnet_vk.php");
                ?>
                <ul>
                    <?if ($facebookLink):?>
                        <li class="fb"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/socnet_facebook.php"), false);?></li>
                        <?endif?>
                    <?if ($twitterLink):?>
                        <li class="tw"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/socnet_twitter.php"), false);?></li>
                        <?endif?>
                    <?if ($googlePlusLink):?>
                        <li class="gp"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/socnet_google.php"), false);?></li>
                        <?endif?>
                    <?if (LANGUAGE_ID=="ru" && $vkLink):?>
                        <li class="vk"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/socnet_vk.php"), false);?></li>
                        <?endif?>
                </ul>
            </div>
            <div class="footer_contact">
                <span><?=GetMessage("FOOTER_COMPANY_PHONE")?>:</span>
                <strong><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/telephone.php"), false);?></strong>
            </div>
        </div>
        <div class="copyright"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/copyright.php"), false);?></div>
    </div>
</div>  <!-- //footer_wrap -->

</div> <!-- //wrap -->

<div class="notive header">
    <a href="javascript:void(0)" onclick="eshopOpenNativeMenu()" class="gn_general_nav notive"></a>
    <a href="<?=SITE_DIR?>personal/cart/" class="cart_link notive"></a>
    <div class="title notive"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_name.php"), false);?></div>
    <div class="clb"></div>
</div>
<div class="menu-page" id="bx_native_menu">
    <div class="menu-items">
        <?$APPLICATION->IncludeComponent("bitrix:menu", "catalog_native", array(
                "ROOT_MENU_TYPE" => "left",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_TIME" => "36000000",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "CACHE_SELECTED_ITEMS" => "N",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MAX_LEVEL" => "3",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N"
                ),
                false
            );?>
        <?$APPLICATION->IncludeComponent("bitrix:menu", "catalog_native", array(
                "ROOT_MENU_TYPE" => "native",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_TIME" => "36000000",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "CACHE_SELECTED_ITEMS" => "N",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MAX_LEVEL" => "3",
                "CHILD_MENU_TYPE" => "personal",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N"
                ),
                false
            );?>
    </div>
	</div>
	<div class="menu_bg" id="bx_menu_bg"></div>
</body>
</html>