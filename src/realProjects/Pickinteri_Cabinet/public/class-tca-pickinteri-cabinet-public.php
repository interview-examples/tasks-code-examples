<?php

namespace TCAby\PickinteriCabinet\PublicPart;

use TCAby\PickinteriCabinet\PublicPart\Auth\Pcab_Auth as Auth;
use TCAby\PickinteriCabinet\Admin\Users\Pcab_AnonymVendor as AnonymVendor;
use TCAby\PickinteriCabinet\Admin\Users\Pcab_AnonymVendors as AnonymVendors;
use TCAby\PickinteriCabinet\Admin\Users\Pcab_User as User;
use TCAby\PickinteriCabinet\Admin\Users\Pcab_Users as Users;
use TCAby\PickinteriCabinet\Admin\Vendors\Pcab_Vendor as Vendor;
use TCAby\PickinteriCabinet\Admin\Vendors\Pcab_Vendors as Vendors;
use TCAby\PickinteriCabinet\Admin\Designers\Pcab_Designer as Designer;
use TCAby\PickinteriCabinet\Admin\Designers\Pcab_Designers as Designers;
use TCAby\PickinteriCabinet\Admin\Business\Pcab_Business;
use TCAby\PickinteriCabinet\Admin\Pcab_Category as Category;
use TCAby\PickinteriCabinet\Admin\Pcab_Categories as Categories;
use TCAby\PickinteriCabinet\Admin\Pcab_AreasOfService as Areas;
use TCAby\PickinteriCabinet\Admin\Pcab_Directories as PCab_Directories;
use TCAby\PickinteriCabinet\Admin\Projects\Pcab_Project as Project;
use TCAby\PickinteriCabinet\Admin\Projects\Pcab_Projects as Projects;
use TCAby\PickinteriCabinet\Admin\Albums\Pcab_Album as Album;
use TCAby\PickinteriCabinet\Admin\Albums\Pcab_Albums as Albums;
use TCAby\PickinteriCabinet\Admin\Reviews\Pcab_Reviews as Reviews;
use TCAby\PickinteriCabinet\Admin\Reviews\Pcab_Review as Review;
use TCAby\PickinteriCabinet\Admin\Pcab_Locations as Locations;
use TCAby\PickinteriCabinet\Admin\Notifications\Pcab_Notifications as Notifications;
use TCAby\PushAlerts;
use TCAby\PickinteriCabinet\Admin\Pcab_InternalMessages as Messages;

class TCA_Pickinteri_Cabinet_Public {

    private string $tca_pickinteri_cabinet;
    private string $version;

    public function __construct(string $tca_pickinteri_cabinet, string $version)
    {
        $this->tca_pickinteri_cabinet = $tca_pickinteri_cabinet;
        $this->version = $version;
    }

    public function enqueue_styles(): void
    {
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css', [], '5.2.1', 'all');
        wp_enqueue_style('trumbowyg-wysiwig', plugin_dir_url(__FILE__) . 'trumbowyg/ui/trumbowyg.css', [], '2.25.2', 'all');
        wp_enqueue_style('trumbowyg-plugin-mention', plugin_dir_url(__FILE__) . 'trumbowyg/plugins/mention/ui/trumbowyg.mention.css', ['trumbowyg-wysiwyg'], '2.25.2');
        wp_enqueue_style($this->tca_pickinteri_cabinet, plugin_dir_url(__FILE__) . 'css/tca-pickinteri-cabinet-public.css', [], $this->version, 'all');
        wp_enqueue_style($this->tca_pickinteri_cabinet . '-switcher', plugin_dir_url(__FILE__) . 'css/tca-pickinteri-cabinet-switcher.css', [], $this->version, 'all');
    }

    public function enqueue_scripts(): void
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js', [], '5.2.1', false);
        wp_enqueue_script('fontawesome-script', 'https://kit.fontawesome.com/b15473cb8f.js', [], '6.0', false);
        wp_enqueue_script('axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', [], $this->version, false);
        wp_enqueue_script('trumbowyg-wysiwyg', plugin_dir_url(__FILE__) . 'trumbowyg/trumbowyg.js', ['jquery'], '2.25.2');
        wp_enqueue_script('trumbowyg-plugin-cleanpaste', plugin_dir_url(__FILE__) . 'trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js', ['trumbowyg-wysiwyg'], '2.9.1');
        wp_enqueue_script('trumbowyg-hebrew-lang', plugin_dir_url(__FILE__) . 'trumbowyg/lng/he.js', ['trumbowyg-wysiwyg'], '2.25.2');
        wp_enqueue_script('trumbowyg-plugin-mention', plugin_dir_url(__FILE__) . 'trumbowyg/plugins/mention/trumbowyg.mention.js', ['trumbowyg-wysiwyg'], '2.25.2');
        wp_enqueue_script('dynamic-fixed-buttons', 'https://cdn.jsdelivr.net/gh/TCAby/DinamicallyAdded-FixedButtons_library@main/tca-dinamicallyadded-fixedbuttons-library.js', ['jquery'], '1.0.4');
        wp_enqueue_script($this->tca_pickinteri_cabinet, plugin_dir_url(__FILE__) . 'js/tca-pickinteri-cabinet-public.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->tca_pickinteri_cabinet, plugin_dir_url(__FILE__) . 'js/multiple-inputs-searchable-dropdown-library.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->tca_pickinteri_cabinet, plugin_dir_url(__FILE__) . 'js/searchable-dropdown-library.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->tca_pickinteri_cabinet, plugin_dir_url(__FILE__) . 'js/searchable_dropdown_library_ver2.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->tca_pickinteri_cabinet, plugin_dir_url(__FILE__) . 'js/checkboxes-multiple-choise-level-sublevel-library.js', ['jquery'], $this->version, false);
    }

    public function user_login2cabinet_callback(): void
    {
        session_start(['cookie_lifetime' => 86400]);
        global $wpdb;

        $form_serizlized = $_REQUEST['form_serialized'];
        parse_str($form_serizlized, $formdata);

        $res = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM `pcab_users` WHERE `user_login` = %s OR `user_email` = %s",
            [$formdata['username'], $formdata['username']]
        ), ARRAY_A);

        if (null === $res) {
            $answer = ['code' => 403, 'message' => __('Wrong username', TCA_PICKINTERI_CABINET), 'data' => json_encode([])];
        } else {
            if (password_verify($formdata['password'], $res['user_pass'])) {
                if (password_needs_rehash($res['user_pass'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($formdata['password'], PASSWORD_DEFAULT);
                    $wpdb->query($wpdb->prepare(
                        "UPDATE `pcab_users` SET `user_pass` = %s WHERE `user_login` = %s",
                        [$newHash, $formdata['username']]
                    ));
                }
                if ($res['activity_status'] === 'approved') {
                    Auth::authSet($res['ID'], $res['pcab_type']);
                    $redirect = Auth::authURLbyUserType();

                    $answer = [
                        'code' => 200,
                        'message' => 'Login is OK',
                        'data' => json_encode([
                            'user_id' => $res['ID'],
                            'first_name' => $res['user_fname'],
                            'last_name' => $res['user_lname'],
                            'redirect_url' => $redirect
                        ])
                    ];
                } else {
                    $answer = [
                        'code' => 202,
                        'message' => 'Login is OK, but the User has not been approved yet. Please, waiting or contact Admin.',
                        'data' => json_encode([
                            'user_id' => $res['ID'],
                            'first_name' => $res['user_fname'],
                            'last_name' => $res['user_lname']
                        ])
                    ];
                }
            } else {
                $answer = ['code' => 403, 'message' => __('Wrong password', TCA_PICKINTERI_CABINET), 'data' => json_encode([])];
            }
        }
        echo json_encode($answer);
        wp_die();
    }

    public function user_upload_media_callback()
    {
        $user_type = $_POST['user_type'];
        if ($user_type == 'admin') {
            $user_id = (int)$_POST['user_id'];
            if (!Users::isUserExist($user_id)) {
                throw new \RuntimeException(sprintf('User ID=%d does not exist.', $user_id));
            }
            $user_type = Users::getUserType($user_id);
        } else {
            session_start();
            $user_id = $_SESSION['user_id'];
            if (is_null($user_id)) $this->customer_logout_callback();
        }

        $http_path = content_url('/uploads/pcab-uploads/' . $user_id);
        $files = $_FILES;

        if (count($files) > 0) {
            $image_type = $_POST['image_type'];
            $allowed_types = $_POST['allowed_types'];
            $done_files = [];
            $upload_dir = WP_CONTENT_DIR . '/uploads/pcab-uploads';

            switch ($image_type) {
                case 'logo':
                    $max_upload_size = 256000;
                    break;
                case 'cover_image':
                    $max_upload_size = 768000;
                    break;
                case 'project_album_content':
                case 'album_content':
                    $max_upload_size = 2097152;
                    break;
                default:
                    $max_upload_size = 2097152;
            }

            if (!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true) && !is_dir($upload_dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $upload_dir));
            }

            $upload_dir .= '/' . trim($user_id);
            if (!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true) && !is_dir($upload_dir)) {
                throw new \RuntimeException(sprintf('Sub-Directory "%s" was not created', $upload_dir));
            }

            foreach ($files as &$file) {
                if ((int)$file['size'] > $max_upload_size) {
                    $answer = ['code' => 300, 'message' => 'Uploaded image(-s) too big. Bigger than ' . $max_upload_size . ' bytes', 'data' => json_encode([])];
                } else {
                    $file_type = explode('/', $file['type'])[0];
                    if (strtolower($allowed_types) !== 'all' && strtolower($allowed_types) !== $file_type) {
                        $answer = ['code' => 300, 'message' => 'Uploaded file has wrong type.', 'data' => json_encode([])];
                    } else {
                        $file['name'] = national_translit($file['name']);
                        if (move_uploaded_file($file['tmp_name'], "$upload_dir/" . $file['name'])) {
                            $done_files[] = realpath("$upload_dir/" . $file['name']);
                            $full_url = $http_path . '/' . $file['name'];
                            $arr_answer = ['files' => $files, 'full_url' => $full_url];

                            switch ($user_type) {
                                case 'designer':
                                    $user = new Designer($user_id);
                                    break;
                                case 'vendor':
                                    $user = new Vendor($user_id);
                                    break;
                            }

                            switch ($image_type) {
                                case 'logo':
                                    $user->setLogo($file);
                                    break;
                                case 'cover_image':
                                    $user->setCoverImage($file);
                                    break;
                                case 'project_album_content':
                                case 'album_content':
                                    $arr_answer['url_media_folder'] = $http_path . '/';
                                    $arr_answer['project_media_id'] = -1;
                                    break;
                            }
                            $answer = ['code' => 200, 'message' => 'Image has been uploaded', 'data' => json_encode($arr_answer)];
                        } else {
                            $answer = ['code' => 300, 'message' => 'Error appeared', 'data' => json_encode([])];
                            break;
                        }
                    }
                }
                if ($image_type == 'logo' || $image_type == 'cover_image') break;  // One image only
            }

            if ($answer['code'] == 200) {
                switch ($user_type) {
                    case 'designer':
                        Designers::refreshCache('personal', ['user_id' => $user_id]);
                        break;
                    case 'vendor':
                        Vendors::refreshCache('personal', ['user_id' => $user_id]);
                        break;
                }
            }
        } else {
            $answer = ['code' => 300, 'message' => 'No image(-s) has been given', 'data' => json_encode([])];
        }

        echo json_encode($answer);
        wp_die();
    }

    public function user_register2cabinet_callback()
    {
        session_abort();
        global $wpdb;

        $form_serizlized = $_REQUEST['form_serialized'];
        parse_str($form_serizlized, $formdata);

        $res = Users::checkIsLoginExists($formdata['newname']);
        if ($res) {
            $answer = ['code' => 412, 'message' => __('This username is already taken.', TCA_PICKINTERI_CABINET), 'data' => json_encode([])];
        } else {
            $res = Users::registerNewUser(
                $formdata['newname'],
                $formdata['user_email'],
                $formdata['password_0'],
                $formdata['user_type'],
                $formdata['user_fname'],
                $formdata['user_lname'],
                $formdata['user_phone']
            );
            if ($res) {
                $answer = ['code' => 200, 'message' => 'Registration is OK', 'data' => json_encode([])];
                PushAlerts\TCA_PushAlerts::sendPushEmail('new_user_2admin', 'he');
            } else {
                $answer = ['code' => 400, 'message' => __('Some kind of database error.', TCA_PICKINTERI_CABINET), 'data' => json_encode([])];
            }
        }
        echo json_encode($answer);
        wp_die();
    }

    public function user_change_suspend_status_itself_callback()
    {
        session_start();
        $user_id = $_SESSION['user_id'];
        if (is_null($user_id)) $this->customer_logout_callback();

        if (!Designers::isUserExist($user_id)) {
            $answer = ['code' => 400, 'message' => "Wrong user id", 'data' => json_encode(['details' => $user_id])];
        } else {
            $designer = new Designer($user_id);
            $designer->setSuspendStatus(!$designer->getSuspendStatus());
            $answer = ['code' => 200, 'message' => "Ok", 'data' => json_encode(['suspend_status' => $designer->getSuspendStatus()])];
        }
        echo json_encode($answer);
        wp_die();
    }

    public function user_save_personal_details_callback() {
        session_start();
        $user_id = $_SESSION['user_id'];
        if (is_null($user_id)) $this->customer_logout_callback();

        global $wpdb;

        $form_serizlized = $_REQUEST['form_serialized'];
        parse_str($form_serizlized, $formdata);

        $user_type = $_POST['user_type'];

        $user_email = htmlspecialchars(strip_tags($formdata['user_email']));
        $user_fname = htmlspecialchars(strip_tags($formdata['user_fname']));
        $user_lname = htmlspecialchars(strip_tags($formdata['user_lname']));
        $user_phone = json_encode(['primary' => htmlspecialchars(strip_tags($formdata['user_phone']))]);

        switch ($user_type) {
            case 'designer':
                $user = new Designer($user_id);
                break;
            case 'vendor':
                $user = new Vendor($user_id);
                break;
        }

        $res = $user->savePersonalDetails(
            $user_email,
            $user_fname,
            $user_lname,
            $user_phone
        );

        if (false === $res) {
            $answer = ['code' => 500, 'message' => 'Internal Server Error', 'data' => json_encode(['error_description' => $wpdb->last_error])];
        } else {
            $answer = ['code' => 200, 'message' => 'Success', 'data' => json_encode([])];
        }

        if ($answer['code'] == 200) {
            switch ($user_type) {
                case 'designer':
                    Designers::refreshCache('personal', ['user_id' => $user_id]);
                    break;
                case 'vendor':
                    Vendors::refreshCache('personal', ['user_id' => $user_id]);
                    break;
            }
        }

        echo json_encode($answer);
        wp_die();
    }

    // Note: other methods below...
}