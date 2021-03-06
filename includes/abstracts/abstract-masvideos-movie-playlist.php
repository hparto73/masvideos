<?php
/**
 * MasVideos Movie Playlist base class.
 *
 * @package MasVideos/Abstracts
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Abstract Movie Playlist Class
 *
 * The MasVideos movie playlist class handles individual movie playlist data.
 *
 * @version  1.0.0
 * @package  MasVideos/Abstracts
 */
class MasVideos_Movie_Playlist extends MasVideos_Data {

    /**
     * This is the name of this object type.
     *
     * @var string
     */
    protected $object_type = 'movie_playlist';

    /**
     * Post type.
     *
     * @var string
     */
    protected $post_type = 'movie_playlist';

    /**
     * Cache group.
     *
     * @var string
     */
    protected $cache_group = 'movie_playlists';

    /**
     * Stores movie playlist data.
     *
     * @var array
     */
    protected $data = array(
        'name'                  => '',
        'slug'                  => '',
        'date_created'          => null,
        'date_modified'         => null,
        'status'                => false,
        'description'           => '',
        'short_description'     => '',
        'parent_id'             => 0,
        'image_id'              => '',
        'movie_ids'             => array(),
    );

    /**
     * Supported features such as 'ajax_add_to_cart'.
     *
     * @var array
     */
    protected $supports = array();

    /**
     * Get the movie playlist if ID is passed, otherwise the movie playlist is new and empty.
     * This class should NOT be instantiated, but the masvideos_get_movie_playlist() function
     * should be used. It is possible, but the masvideos_get_movie_playlist() is preferred.
     *
     * @param int|MasVideos_Movie_Playlist|object $movie_playlist Movie Playlist to init.
     */
    public function __construct( $movie_playlist = 0 ) {
        parent::__construct( $movie_playlist );
        if ( is_numeric( $movie_playlist ) && $movie_playlist > 0 ) {
            $this->set_id( $movie_playlist );
        } elseif ( $movie_playlist instanceof self ) {
            $this->set_id( absint( $movie_playlist->get_id() ) );
        } elseif ( ! empty( $movie_playlist->ID ) ) {
            $this->set_id( absint( $movie_playlist->ID ) );
        } else {
            $this->set_object_read( true );
        }

        $this->data_store = MasVideos_Data_Store::load( 'movie_playlist' );
        if ( $this->get_id() > 0 ) {
            $this->data_store->read( $this );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    |
    | Methods for getting data from the movie playlist object.
    */

    /**
     * Get movie playlist name.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return string
     */
    public function get_name( $context = 'view' ) {
        return $this->get_prop( 'name', $context );
    }

    /**
     * Get movie playlist slug.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return string
     */
    public function get_slug( $context = 'view' ) {
        return $this->get_prop( 'slug', $context );
    }

    /**
     * Get movie playlist created date.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return MasVideos_DateTime|NULL object if the date is set or null if there is no date.
     */
    public function get_date_created( $context = 'view' ) {
        return $this->get_prop( 'date_created', $context );
    }

    /**
     * Get movie playlist modified date.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return MasVideos_DateTime|NULL object if the date is set or null if there is no date.
     */
    public function get_date_modified( $context = 'view' ) {
        return $this->get_prop( 'date_modified', $context );
    }

    /**
     * Get movie playlist status.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return string
     */
    public function get_status( $context = 'view' ) {
        return $this->get_prop( 'status', $context );
    }

    /**
     * Get movie playlist description.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return string
     */
    public function get_description( $context = 'view' ) {
        return $this->get_prop( 'description', $context );
    }

    /**
     * Get movie playlist short description.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return string
     */
    public function get_short_description( $context = 'view' ) {
        return $this->get_prop( 'short_description', $context );
    }

    /**
     * Get parent ID.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return int
     */
    public function get_parent_id( $context = 'view' ) {
        return $this->get_prop( 'parent_id', $context );
    }

    /**
     * Get main image ID.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return string
     */
    public function get_image_id( $context = 'view' ) {
        return $this->get_prop( 'image_id', $context );
    }

    /**
     * Get movie ids.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are view and edit.
     * @return array
     */
    public function get_movie_ids( $context = 'view' ) {
        return $this->get_prop( 'movie_ids', $context );
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    |
    | Functions for setting movie playlist data. These should not update anything in the
    | database itself and should only change what is stored in the class
    | object.
    */

    /**
     * Set movie playlist name.
     *
     * @since 1.0.0
     * @param string $name Movie Playlist name.
     */
    public function set_name( $name ) {
        $this->set_prop( 'name', $name );
    }

    /**
     * Set movie playlist slug.
     *
     * @since 1.0.0
     * @param string $slug Movie Playlist slug.
     */
    public function set_slug( $slug ) {
        $this->set_prop( 'slug', $slug );
    }

    /**
     * Set movie playlist created date.
     *
     * @since 1.0.0
     * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
     */
    public function set_date_created( $date = null ) {
        $this->set_date_prop( 'date_created', $date );
    }

    /**
     * Set movie playlist modified date.
     *
     * @since 1.0.0
     * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
     */
    public function set_date_modified( $date = null ) {
        $this->set_date_prop( 'date_modified', $date );
    }

    /**
     * Set movie playlist status.
     *
     * @since 1.0.0
     * @param string $status Movie Playlist status.
     */
    public function set_status( $status ) {
        $this->set_prop( 'status', $status );
    }

    /**
     * Set movie playlist description.
     *
     * @since 1.0.0
     * @param string $description Movie Playlist description.
     */
    public function set_description( $description ) {
        $this->set_prop( 'description', $description );
    }

    /**
     * Set movie playlist short description.
     *
     * @since 1.0.0
     * @param string $short_description Movie Playlist short description.
     */
    public function set_short_description( $short_description ) {
        $this->set_prop( 'short_description', $short_description );
    }

    /**
     * Set parent ID.
     *
     * @since 1.0.0
     * @param int $parent_id Movie Playlist parent ID.
     */
    public function set_parent_id( $parent_id ) {
        $this->set_prop( 'parent_id', absint( $parent_id ) );
    }

    /**
     * Set menu order.
     *
     * @since 1.0.0
     * @param int $menu_order Menu order.
     */
    public function set_menu_order( $menu_order ) {
        $this->set_prop( 'menu_order', intval( $menu_order ) );
    }

    /**
     * Set main image ID.
     *
     * @since 1.0.0
     * @param int|string $image_id Movie Playlist image id.
     */
    public function set_image_id( $image_id = '' ) {
        $this->set_prop( 'image_id', $image_id );
    }

    /**
     * Set the movie playlist movies.
     *
     * @since 1.0.0
     * @param array $movie_ids List of movies IDs.
     */
    public function set_movie_ids( $movie_ids ) {
        $this->set_prop( 'movie_ids', array_unique( array_map( 'intval', $movie_ids ) ) );
    }

    /*
    |--------------------------------------------------------------------------
    | Other Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Ensure properties are set correctly before save.
     *
     * @since 1.0.0
     */
    public function validate_props() {
    }

    /**
     * Save data (either create or update depending on if we are working on an existing movie playlist).
     *
     * @since 1.0.0
     * @return int
     */
    public function save() {
        $this->validate_props();

        if ( $this->data_store ) {
            // Trigger action before saving to the DB. Use a pointer to adjust object props before save.
            do_action( 'masvideos_before_' . $this->object_type . '_object_save', $this, $this->data_store );

            if ( $this->get_id() ) {
                $this->data_store->update( $this );
            } else {
                $this->data_store->create( $this );
            }
            if ( $this->get_parent_id() ) {
                masvideos_deferred_movie_playlist_sync( $this->get_parent_id() );
            }
        }
        return $this->get_id();
    }

    /*
    |--------------------------------------------------------------------------
    | Conditionals
    |--------------------------------------------------------------------------
    */

    /**
     * Check if a movie playlist supports a given feature.
     *
     * Movie Playlist classes should override this to declare support (or lack of support) for a feature.
     *
     * @param string $feature string The name of a feature to test support for.
     * @return bool True if the movie playlist supports the feature, false otherwise.
     * @since 2.5.0
     */
    public function supports( $feature ) {
        return apply_filters( 'masvideos_movie_playlist_supports', in_array( $feature, $this->supports ), $feature, $this );
    }

    /**
     * Returns whether or not the movie playlist post exists.
     *
     * @return bool
     */
    public function exists() {
        return false !== $this->get_status();
    }

    /**
     * Returns whether or not the movie playlist is visible in the catalog.
     *
     * @return bool
     */
    public function is_visible() {
        $visible = true;

        if ( 'trash' === $this->get_status() ) {
            $visible = false;
        } elseif ( 'publish' !== $this->get_status() && ! current_user_can( 'edit_post', $this->get_id() ) ) {
            $visible = false;
        }

        if ( $this->get_parent_id() ) {
            $parent_movie_playlist = masvideos_get_movie_playlist( $this->get_parent_id() );

            if ( $parent_movie_playlist && 'publish' !== $parent_movie_playlist->get_status() ) {
                $visible = false;
            }
        }

        return apply_filters( 'masvideos_movie_playlist_is_visible', $visible, $this->get_id() );
    }

    /**
     * Returns whether or not the movie playlist has any child movie playlist.
     *
     * @return bool
     */
    public function has_child() {
        return 0 < count( $this->get_children() );
    }

    /*
    |--------------------------------------------------------------------------
    | Non-CRUD Getters
    |--------------------------------------------------------------------------
    */

    /**
     * Get the movie playlist's title. For movie playlists this is the movie playlist name.
     *
     * @return string
     */
    public function get_title() {
        return apply_filters( 'masvideos_movie_playlist_title', $this->get_name(), $this );
    }

    /**
     * Movie Playlist permalink.
     *
     * @return string
     */
    public function get_permalink() {
        return get_permalink( $this->get_id() );
    }

    /**
     * Returns the children IDs if applicable. Overridden by child classes.
     *
     * @return array of IDs
     */
    public function get_children() {
        return array();
    }

    /**
     * Returns the main movie playlist image.
     *
     * @param string $size (default: 'masvideos_thumbnail').
     * @param array  $attr Image attributes.
     * @param bool   $placeholder True to return $placeholder if no image is found, or false to return an empty string.
     * @return string
     */
    public function get_image( $size = 'masvideos_thumbnail', $attr = array(), $placeholder = true ) {
        if ( $this->get_image_id() ) {
            $image = wp_get_attachment_image( $this->get_image_id(), $size, false, $attr );
        } elseif ( $this->get_parent_id() ) {
            $parent_movie_playlist = masvideos_get_movie_playlist( $this->get_parent_id() );
            $image          = $parent_movie_playlist->get_image();
        } elseif ( $placeholder ) {
            $image = masvideos_placeholder_img( $size );
        } else {
            $image = '';
        }

        return apply_filters( 'masvideos_movie_playlist_get_image', $image, $this, $size, $attr, $placeholder, $image );
    }
}
