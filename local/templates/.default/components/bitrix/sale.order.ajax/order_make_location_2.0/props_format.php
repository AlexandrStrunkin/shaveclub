<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?  global $USER;

    if ($USER->IsAuthorized()) {
        $userID = $USER->GetID();
        $userProps = CUser::GetByID($userID);
        $arUserProps = $userProps->Fetch();
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            email = '<?= $arUserProps["EMAIL"] ?>';
            name = '<?= $_SESSION["FIO"] ?>';
            phone = '<?= $_SESSION["PHONE"] ?>';
            address = '<?= $_SESSION["ADDRESS"] ?>';
            comment = '<?= $_SESSION["COMMENT"] ?>';
            zip = '<?= $_SESSION["ZIP"] ?>';
            city = '<?= $_SESSION["CITY"] ?>';
            if (phone != '') {
                $("#ORDER_PROP_3").val(phone);
            }
            if (name != '') {
                $("#ORDER_PROP_1").val(name);
            }
            if (email != '') {
                $("#ORDER_PROP_2").val(email);
            }
            if (address != '') {
                $("#ORDER_PROP_7").val(address);
            }
            if (comment != '') {
                $("#ORDER_PROP_69").val(comment);
            }
            if (zip != '') {
                $("#ORDER_PROP_4").val(zip);
            }
            if (city != '') {
                $("#ORDER_PROP_6").val(city);
            }
            $(".bx-ui-sls-fake").attr("placeholder", "Введите название населенного пункта для выбора способа доставки...");

        });
    </script>

    <?   }

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
                    if ($arProperties["CODE"] == "ZIP"){ }

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
                    <? if ($_POST["ORDER_PROP_4"]) {
                         $_SESSION['ZIP'] = $_POST["ORDER_PROP_4"];
                    } ?>

                    <?if ($_SESSION[$arProperties["CODE"]] != '') {
                        $propValue = $_SESSION[$arProperties["CODE"]];
                    } else {
                        $propValue = $arProperties["VALUE"];
                    } ?>

                    <label >

                        <input type="text"
                        placeholder="<?=$arProperties["NAME"]?>"
                        class="<?if($arProperties["CODE"] != "KOMMENT"){?>input<?}?> koment" size="<?=$arProperties["SIZE1"]?>"
                        value="<?=$propValue?>"
                        name="<?=$arProperties["FIELD_NAME"]?>"
                        id="<?=$arProperties["FIELD_NAME"]?>"
                        autocomplete="off">

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
                    elseif ($arProperties["TYPE"] == "LOCATION") {
                        $value = 0;
                        if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                            foreach ($arProperties["VARIANTS"] as $arVariant) {
                                if ($arVariant["SELECTED"] == "Y") {
                                    if ($_SESSION["CITY"] != '') {
                                        $value = $_SESSION["CITY"];
                                    } else {
                                        $value = $arVariant["ID"];
                                    }
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

                        CSaleLocation::proxySaleAjaxLocationsComponent(array(
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
                            array(
                                "ID" => $value,
                                "CODE" => "",
                                "SHOW_DEFAULT_LOCATIONS" => "Y",

                                // function called on each location change caused by user or by program
                                // it may be replaced with global component dispatch mechanism coming soon
                                "JS_CALLBACK" => "submitFormProxy",

                                // function window.BX.locationsDeferred['X'] will be created and lately called on each form re-draw.
                                // it may be removed when sale.order.ajax will use real ajax form posting with BX.ProcessHTML() and other stuff instead of just simple iframe transfer
                                "JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),

                                // an instance of this control will be placed to window.BX.locationSelectors['X'] and lately will be available from everywhere
                                // it may be replaced with global component dispatch mechanism coming soon
                                "JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),

                                "DISABLE_KEYBOARD_INPUT" => "Y",
                                "PRECACHE_LAST_LEVEL" => "Y",
                            ),
                            $locationTemplateP,
                            true,
                            'location-block-wrapper'
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
