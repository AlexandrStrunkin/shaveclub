<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>  
<?
    if (!function_exists("showFilePropertyField"))
    {
        function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000)
        {
            $res = "";

            if (!is_array($values) || empty($values))
                $values = array(
                    "n0" => 0,
                );

            if ($property_fields["MULTIPLE"] == "N")
            {
                $res = "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
            }
            else
            {
                $res = '
                <script type="text/javascript">
                function addControl(item)
                {
                var current_name = item.id.split("[")[0],
                current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
                next_id = parseInt(current_id) + 1;

                var newInput = document.createElement("input");
                newInput.type = "file";
                newInput.name = current_name + "[" + next_id + "]";
                newInput.id = current_name + "[" + next_id + "]";
                newInput.onchange = function() { addControl(this); };

                var br = document.createElement("br");
                var br2 = document.createElement("br");

                BX(item.id).parentNode.appendChild(br);
                BX(item.id).parentNode.appendChild(br2);
                BX(item.id).parentNode.appendChild(newInput);
                }
                </script>
                ';

                $res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
                $res .= "<br/><br/>";
                $res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\"></label>";
            }

            return $res;
        }
    }

    if (!function_exists("PrintPropsForm"))
    {
        function PrintPropsForm($arSource = array(), $locationTemplate = ".default")
        {
            if (!empty($arSource))
            {
            ?>
            <?
                foreach ($arSource as $arProperties)
                { 
                    
                    if ($arProperties["CODE"] == "ZIP"){  /*
                    ?>
                    <span class="title address_title">адрес доставки</span>
                    <?
                   */ }
                    
                    if ($arProperties["TYPE"] == "CHECKBOX")
                    {
                    ?>
                    <input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">

                    <?=$arProperties["NAME"]?>
                    <?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
                        <span class="bx_sof_req">*</span>
                        <?endif;?>

                    <input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>

                    <?
                        if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                        ?>
                        <div class="bx_description">
                            <?=$arProperties["DESCRIPTION"]?>
                        </div>
                        <?
                            endif;
                    ?>        
                    <?
                    }
                    elseif ($arProperties["TYPE"] == "TEXT")
                    {
                    ?> 	
                    <label>
                        <input type="text" placeholder="<?=$arProperties["NAME"]?>" class="input" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>">
                    </label>  
                    <?
                    }
                    elseif ($arProperties["TYPE"] == "SELECT")
                    {
                    ?>    							
                    <select name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                        <?
                            foreach($arProperties["VARIANTS"] as $arVariants):
                            ?>
                            <option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
                            <?
                                endforeach;
                        ?>
                    </select>  								
                    <div style="clear: both;"></div>
                    <?
                    }
                    elseif ($arProperties["TYPE"] == "MULTISELECT")
                    {
                    ?>   							
                    <select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                        <?
                            foreach($arProperties["VARIANTS"] as $arVariants):
                            ?>
                            <option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
                            <?
                                endforeach;
                        ?>
                    </select>                                            								
                    <div style="clear: both;"></div>
                    <?
                    }
                    elseif ($arProperties["TYPE"] == "TEXTAREA")
                    {
                        $rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
                    ?>         							
                    <textarea rows="<?=$rows?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
                    <div style="clear: both;"></div>
                    <?
                    }
                    elseif ($arProperties["TYPE"] == "LOCATION")
                    {
                        $value = 0;
                        if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
                        {
                            foreach ($arProperties["VARIANTS"] as $arVariant)
                            {
                                if ($arVariant["SELECTED"] == "Y")
                                {
                                    $value = $arVariant["ID"];
                                    break;
                                }
                            }
                        }
                    ?>

                    <?
                        $GLOBALS["APPLICATION"]->IncludeComponent(
                            "bitrix:sale.ajax.locations",
                            "order_locations",
                            array(
                                "AJAX_CALL" => "N",
                                "COUNTRY_INPUT_NAME" => "COUNTRY",
                                "REGION_INPUT_NAME" => "REGION",
                                "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                                "CITY_OUT_LOCATION" => "Y",
                                "LOCATION_VALUE" => $value,
                                "ORDER_PROPS_ID" => $arProperties["ID"],
                                "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                "SIZE1" => $arProperties["SIZE1"],
                            ),
                            null,
                            array('HIDE_ICONS' => 'Y')
                        );
                    ?>    								
                    <div style="clear: both;"></div>
                    <?
                    }
                    elseif ($arProperties["TYPE"] == "RADIO")
                    {
                    ?>
                    <?=$arProperties["NAME"]?>
                    <?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
                        <span class="bx_sof_req">*</span>
                        <?endif;?>

                    <?
                        if (is_array($arProperties["VARIANTS"]))
                        {
                            foreach($arProperties["VARIANTS"] as $arVariants):
                            ?>
                            <input
                                type="radio"
                                name="<?=$arProperties["FIELD_NAME"]?>"
                                id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"
                                value="<?=$arVariants["VALUE"]?>" <?if($arVariants["CHECKED"] == "Y") echo " checked";?> />

                            <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label></br>
                            <?
                                endforeach;
                        }
                    ?>      								
                    <div style="clear: both;"></div>
                    <?
                    }
                    elseif ($arProperties["TYPE"] == "FILE")
                    {
                    ?>

                    <?=$arProperties["NAME"]?>
                    <?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
                        <span class="bx_sof_req">*</span>
                        <?endif;?>

                    <?=showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"])?>

                    <?
                        if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                        ?>
                        <div class="bx_description">
                            <?=$arProperties["DESCRIPTION"]?>
                        </div>
                        <?
                            endif;
                    ?>


                    <div style="clear: both;"></div>
                    <?
                    }
                }     					
            }
        }
    }
?>