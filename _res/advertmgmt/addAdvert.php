<?php

    if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

    if( $_GET['id'] ) {

        $id = $core->clean( $_GET['id'] );

        $query = $db->query( "SELECT * FROM adverts WHERE id = '{$id}'" );
        $data  = $db->assoc( $query );

        $editid = $data['id'];

    }

?>
<form action="" method="post" id="addAdvert">

    <div class="box">

        <div class="square title">
            <strong>Add advert</strong>
        </div>

        <?php

            if( $_POST['submit'] ) {

                try {

                    $ad_nam = $core->clean( $_POST['ad_nam'] );
                    $ad_url = $core->clean( $_POST['ad_url'] );
                    $ad_img = $core->clean( $_POST['ad_img'] );
                    $ad_imp = $core->clean( $_POST['ad_imp'] );
                    $ad_mimp = $core->clean( $_POST['ad_maximp'] );
                    $ad_act = $core->clean( $_POST['ad_act'] );
              
                    $query = $db->query( "SELECT * FROM adverts" );

                    if( !$ad_nam or !$ad_url or !$ad_img ) {

                        throw new Exception( "All fields are required." );

                    }
                    else {

                        if( $editid ) {

                            $db->query( "UPDATE adverts SET name = '{$ad_nam}', url = '{$ad_url}', imgurl = '{$ad_img}', impressions = '{$ad_imp}', max_impressions = '{$ad_mimp}', active = '{$ad_act}' WHERE id = '{$editid}'" );

                            echo "<div class=\"square good\">";
                            echo "<strong>Success</strong>";
                            echo "<br />";
                            echo "Advert updated!";
                            echo "</div>";

                        }
                        else {

                            $db->query( "INSERT INTO adverts VALUES (NULL, '{$ad_nam}', '{$ad_url}', '{$ad_img}', '{$ad_imp}', '{$ad_mimp}', '{$ad_act}');" );

                            echo "<div class=\"square good\">";
                            echo "<strong>Success</strong>";
                            echo "<br />";
                            echo "Advert added!";
                            echo "</div>";

                        }

                    }

                }
                catch( Exception $e ) {

                    echo "<div class=\"square bad\">";
                    echo "<strong>Error</strong>";
                    echo "<br />";
                    echo $e->getMessage();
                    echo "</div>";

                }

            }

        ?>

        <table width="100%" cellpadding="3" cellspacing="0">
            <?php
            
                echo $core->buildField( "text",
                                        "required",
                                        "ad_nam",
                                        "Name",
                                        "Name of the advert",
                                        $data['name'] );

                echo $core->buildField( "text",
                                        "required",
                                        "ad_url",
                                        "URL",
                                        "URL advert links to",
                                        $data['url'] );

                echo $core->buildField( "text",
                                        "required",
                                        "ad_img",
                                        "Image URL",
                                        "Image URL",
                                        $data['imgurl'] );

                echo $core->buildField( "text",
                                        "",
                                        "ad_imp",
                                        "Impressions",
                                        "Number of advert impressions.",
                                        $data['impressions'] );

                echo $core->buildField( "text",
                                        "",
                                        "ad_maximp",
                                        "Maximum Impressions",
                                        "Max number of impressions (0 = Off).",
                                        $data['max_impressions'] );

                echo $core->buildField( "text",
                                        "",
                                        "ad_act",
                                        "Active",
                                        "Set to 0 to disable.",
                                        $data['active'] );
            ?>
        </table>

    </div>

    <div class="box" align="right">

        <input class="button" type="submit" name="submit" value="Submit" />

    </div>

</form>

<?php
    echo $core->buildFormJS('addAdvert');

?>