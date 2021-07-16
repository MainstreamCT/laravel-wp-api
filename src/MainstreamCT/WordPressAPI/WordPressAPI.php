<?php namespace MainstreamCT\WordPressAPI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Str;

class WordPressAPI {

    /**
     * Guzzle client
     * @var Client
     */
    protected $client;

    /**
     * WP-WPI endpoint URL
     * @var string
     */
    protected $endpoint;

    /**
     * Auth headers
     * @var string
     */
    protected $auth;

    /**
     * Constructor
     *
     * @param string $endpoint
     * @param Client $client
     * @param string $auth
     */
    public function __construct($endpoint, Client $client, $auth = null) {
        $this->endpoint = $endpoint;
        $this->client   = $client;
        $this->auth     = $auth;
    }

    /**
     * Get all posts
     *
     * @param  int $page
     * @return array
     */
    public function getPosts($page = null) {
        return $this->get('posts', ['page' => $page]);
    }
    /**
     * Get all sticky posts
     *
     * @param  int $page
     * @return array
     */
    public function getStickies($page = null) {
        return $this->get('posts', ['sticky' => true, 'page' => $page]);
    }
    
    /**
     * Get all pages
     *
     * @param  int $page
     * @return array
     */
    public function getPages($page = null) {
        return $this->get('pages', ['page' => $page]);
    }

    /**
     * Get post by id
     *
     * @param  int $id
     * @return array
     */
    public function getPostByID($id) {
        return $this->get("posts/$id");
    }

    /**
     * Get post by slug
     *
     * @param  string $slug
     * @return array
     */
    public function getPostBySlug($slug) {
        return $this->get('posts', ['slug' => $slug]);
    }

    /**
     * Get page by slug
     *
     * @param  string $slug
     * @return array
     */
    public function getPageBySlug($slug) {
        return $this->get('posts', ['type' => 'page', 'filter' => ['name' => $slug]]);
    }

    /**
     * Get all categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->get('categories',['per_page' => 99]);
    }

    /**
     * Get all tags
     *
     * @return array
     */
    public function getTags($tag=null) {
        if($tag > 0){
          return $this->get('tags/'.$tag, ['per_page' => 99]);
        }
        return $this->get('tags', ['per_page' => 99]);
    }
    
    /**
     * Get posts from categories within a parent category
     *
     * @param  string $parent
     * @param  int $page
     * @return array
     */
    public function getPostsFromChildren($parent = null, $page = null, $pp = null) {
      return $this->get('posts', ['parent' => trim($parent),'page' => $page, 'per_page' => $pp]);
    }

    /**
     * Get posts from category
     *
     * @param  string $cat
     * @param  int $page
     * @return array
     */
    public function getPostsByCategory($cat = null, $page = null, $pp = null) {
        return $this->get('posts', ['categories' => trim($cat),'page' => $page, 'per_page' => $pp]);
    }

    /**
     * Get posts by author
     *
     * @param  string $name
     * @param  int $page
     * @return array
     */
    public function getPostsByAuthor($name, $page = null) {
        return $this->get('posts', ['page' => $page, 'filter' => ['author_name' => $name]]);
    }

    /**
     * Get latest post from category
     *
     * @param  string $cat
     * @return array
     */
    public function getLatestPostFromCategory($cat = null) {
        return $this->get('posts', ['categories' => trim($cat),'per_page' => 1,'status' => 'publish']);
    }
    
    /**
     * Get posts tagged with tag
     *
     * @param  string $tags
     * @param  int $page
     * @return array
     */
    public function getPostsByTags($tags, $page = null) {
        return $this->get('posts', ['tags' => $tags]);
    }

    /**
     * Search posts
     *
     * @param  string $query
     * @param  int $page
     * @return array
     */
    public function searchPosts($query, $page = null) {
        return $this->get('posts', ['page' => $page, 'filter' => ['s' => $query]]);
    }

    /**
     * Get posts by date
     *
     * @param  int $year
     * @param  int $month
     * @param  int $page
     * @return array
     */
    public function getPostsByDate($year, $month, $page = null) {
        return $this->get('posts', ['page' => $page, 'filter' => ['year' => $year, 'monthnum' => $month]]);
    }

    /**
     * Deploy a new MultiSite tenant, with random credentials
     * 
     * @param string $siteName
     * @param string $blogTitle
     * @param string $email
     * @param string $password
     * 
     */
    public function deploy($siteName, $blogTitle, $email, $password) {
        return $this->post('wp-content/plugins/multisite-json-api/endpoints/create-site.php', ['email' => $email, 'site_name' => $siteName, 'title' => $blogTitle, 'password' => $password]);
    }

    /**
     * Get data from the API
     *
     * @param  string $method
     * @param  array  $query
     * @return array
     */
    public function get($method, array $query = array()) {
        try {
            $query = ['query' => $query];

            if ($this->auth) {
                $query['auth'] = $this->auth;
            }

            $response = $this->client->get($this->endpoint . $method, $query);

            $return = [
                'results' => json_decode((string) $response->getBody(), true),
                'total'   => $response->getHeaderLine('X-WP-Total'),
                'pages'   => $response->getHeaderLine('X-WP-TotalPages')
            ];
        } catch (RequestException $e) {

            $error['message'] = $e->getMessage();

            if ($e->getResponse()) {
                $error['code'] = $e->getResponse()->getStatusCode();
            }

            $return = [
                'error'   => $error,
                'results' => [],
                'total'   => 0,
                'pages'   => 0
            ];

        }

        return $return;

    }

    /**
     * Post data to the API
     *
     * @param  string $path
     * @param  array  $body
     * @return array
     */
    public function post($path, array $body = []) {
        try {
            $body = ['query' => $body];

            if ($this->auth) {
                $query['auth'] = $this->auth;
            }

            $response = $this->client->post($this->endpoint . $method, $body);

            $return = [
                'results' => json_decode((string) $response->getBody(), true)
            ];
        } catch (RequestException $e) {
            $error['message'] = $e->getMessage();

            if ($e->getResponse()) {
                $error['code'] = $e->getResponse()->getStatusCode();
            }

            $return = [
                'error'   => $error,
                'results' => []
            ];
        }

        return $return;
    }
}
