<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 18.04.2016
	 * Time: 13:46
	 */

	if (!class_exists('hw_theme_switcher')) {
		/**
		 * @return hw_theme_switcher
		 */
		function hw_theme_switcher() {
			static $class;
			if (!$class instanceof hw_theme_switcher) $class = new hw_theme_switcher();
			return $class;
		}

		class hw_theme_switcher {


			private $oldThemeSlug;

			private $do_themeSwitchStr;

			/**
			 * Устанавливает тему по слагу
			 * @param null $themeSlug
			 * @return bool
			 */
			public function do_setTheme($themeSlug = null) {
				if (!trim($this->do_themeSwitchStr == '')) {
					return $this->do_themeSwitchStr;
				} elseif (!trim($themeSlug) == '' && trim($this->do_themeSwitchStr) == '') {

					$this->do_themeSwitchStr = $themeSlug;
					add_filter('template', array($this, 'do_setTheme'));
					add_filter('option_template', array($this, 'do_setTheme'));
					add_filter('option_stylesheet', array($this, 'do_setTheme'));
					///
					return true;
				} else return !empty($this->do_themeSwitchStr);
			}

			/**
			 * Возвращает слаг текущей темы
			 * @return mixed|void
			 */
			public function getStr_currentThemeSlug() {
				return $this->oldThemeSlug;
			}

			/**
			 * Запускает процесс инициализации
			 */
			public function init() {
				$this->oldThemeSlug = get_option('template');
				$ruleThemeSlug = $this->get_testCurrentRuleMath();
				if ($ruleThemeSlug !== false && $this->getStr_currentThemeSlug() != $ruleThemeSlug) {
					$this->do_setTheme($ruleThemeSlug);
				}
			}

			/**
			 * Возвращает массив правил
			 * @return array
			 */
			public function getArr_rules() {
				$R = array();
				$rules = get_posts(array('post_type' => HW_THEME_SWITCHER_POST_TYPE, 'posts_per_page' => -1));
				foreach ($rules as $rule) {
					$R[$rule->ID] = array(
						'post' => $rule,
						'ID' => $rule->ID,
						'post_types' => get_post_meta($rule->ID, HW_THEME_SWITCHER_PREFIX . '_post_types', true),
						'post_ids' => get_post_meta($rule->ID, HW_THEME_SWITCHER_PREFIX . '_post_ids', true),
						'theme_slug' => get_post_meta($rule->ID, 'hw_theme_switcher_theme_id', true),
						'theme' => $my_theme = wp_get_theme(get_post_meta($rule->ID, 'hw_theme_switcher_theme_id', true))
					);
				}
				return $R;
			}


			/**
			 * Тест, попадает ли текущий запрос под правила
			 */
			public function get_testCurrentRuleMath() {
				$currentId = $this->get_currentPostId();
				if($currentId == 0) return false;
				$queriedObject = get_post($currentId);
				if ($queriedObject instanceof WP_Post && $queriedObject->post_type != HW_THEME_SWITCHER_POST_TYPE) {
					$rule = $this->get_ruleByPostId($queriedObject->ID);
					return (is_array($rule) ? $rule['theme_slug'] : false);
				}
				return true;
			}

			/**
			 * Возвращает массив правила по ID тестового поста, либо FLASE, если правило не найдено
			 * @param $post_id
			 * @return bool|mixed
			 */
			public function get_ruleByPostId($post_id) {
				foreach ($this->getArr_rules() as $rule) {
					if (is_array($rule['post_ids'])) {
						$post_IDs = array_flip($rule['post_ids']);
						if (isset($post_IDs[$post_id])) return $rule;
					}
				}
				return false;
			}


			/**
			 * Возвращает массив всех задействованных типов постов в правилах
			 * @return array
			 */
			public function getArr_rulePostTypes() {
				$R = array();
				foreach ($this->getArr_rules() as $rule) {
					if (is_array($rule['post_types'])) $R = array_merge($R, $rule['post_types']);
				}
				return array_unique($R);
			}

			/**
			 * Возвращает текущий адрес URL
			 */
			public function getStr_urlFull() {
				$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
				return rtrim('http' . ($https ? 's' : '') . '://' . $_SERVER['HTTP_HOST'], '/') . $_SERVER['REQUEST_URI'];
			}

			/**
			 * Examine a URL and try to determine the post ID it represents.
			 *
			 * Checks are supposedly from the hosted site blog.
			 *
			 * @since 1.0.0
			 *
			 * @global WP_Rewrite $wp_rewrite
			 * @global WP $wp
			 *
			 * @return int Post ID, or 0 on failure.
			 */
			public function get_currentPostId() {
				include_once dirname(WP_CONTENT_DIR).'/wp-includes/pluggable.php';
				$wp_rewrite = new WP_Rewrite();
				$wp_rewrite->init();
				$url = $this->getStr_urlFull();

				/**
				 * Filter the URL to derive the post ID from.
				 *
				 * @since 2.2.0
				 *
				 * @param string $url The URL to derive the post ID from.
				 */
				$url = apply_filters('url_to_postid', $url);

				// First, check to see if there is a 'p=N' or 'page_id=N' to match against
				if (preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values)) {
					$id = absint($values[2]);
					if ($id)
						return $id;
				}

				// Get rid of the #anchor
				$url_split = explode('#', $url);
				$url = $url_split[0];

				// Get rid of URL ?query=string
				$url_split = explode('?', $url);
				$url = $url_split[0];

				// Set the correct URL scheme.
				$scheme = parse_url(home_url(), PHP_URL_SCHEME);
				$url = set_url_scheme($url, $scheme);

				// Add 'www.' if it is absent and should be there
				if (false !== strpos(home_url(), '://www.') && false === strpos($url, '://www.'))
					$url = str_replace('://', '://www.', $url);

				// Strip 'www.' if it is present and shouldn't be
				if (false === strpos(home_url(), '://www.'))
					$url = str_replace('://www.', '://', $url);

				if (trim($url, '/') === home_url() && 'page' == get_option('show_on_front')) {
					$page_on_front = get_option('page_on_front');

					if ($page_on_front && get_post($page_on_front) instanceof WP_Post) {
						return (int)$page_on_front;
					}
				}

				// Check to see if we are using rewrite rules
				$rewrite = $wp_rewrite->wp_rewrite_rules();

				// Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
				if (empty($rewrite))
					return 0;

				// Strip 'index.php/' if we're not using path info permalinks
				if (!$wp_rewrite->using_index_permalinks())
					$url = str_replace($wp_rewrite->index . '/', '', $url);

				if (false !== strpos(trailingslashit($url), home_url('/'))) {
					// Chop off http://domain.com/[path]
					$url = str_replace(home_url(), '', $url);
				} else {
					// Chop off /path/to/blog
					$home_path = parse_url(home_url('/'));
					$home_path = isset($home_path['path']) ? $home_path['path'] : '';
					$url = preg_replace(sprintf('#^%s#', preg_quote($home_path)), '', trailingslashit($url));
				}

				// Trim leading and lagging slashes
				$url = trim($url, '/');

				$request = $url;
				$post_type_query_vars = array();

				foreach (get_post_types(array(), 'objects') as $post_type => $t) {
					if (!empty($t->query_var))
						$post_type_query_vars[$t->query_var] = $post_type;
				}

				// Look for matches.
				$request_match = $request;
				foreach ((array)$rewrite as $match => $query) {

					// If the requesting file is the anchor of the match, prepend it
					// to the path info.
					if (!empty($url) && ($url != $request) && (strpos($match, $url) === 0))
						$request_match = $url . '/' . $request;

					if (preg_match("#^$match#", $request_match, $matches)) {

						if ($wp_rewrite->use_verbose_page_rules && preg_match('/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch)) {
							// This is a verbose page match, let's check to be sure about it.
							$page = get_page_by_path($matches[$varmatch[1]]);
							if (!$page) {
								continue;
							}

							$post_status_obj = get_post_status_object($page->post_status);
							if (!$post_status_obj->public && !$post_status_obj->protected
								&& !$post_status_obj->private && $post_status_obj->exclude_from_search
							) {
								continue;
							}
						}

						// Got a match.
						// Trim the query of everything up to the '?'.
						$query = preg_replace("!^.+\?!", '', $query);

						// Substitute the substring matches into the query.
						$query = addslashes(WP_MatchesMapRegex::apply($query, $matches));

						// Filter out non-public query vars
						$wp = new WP();
						parse_str($query, $query_vars);
						$query = array();
						foreach ((array)$query_vars as $key => $value) {
							if (in_array($key, $wp->public_query_vars)) {
								$query[$key] = $value;
								if (isset($post_type_query_vars[$key])) {
									$query['post_type'] = $post_type_query_vars[$key];
									$query['name'] = $value;
								}
							}
						}

						// Resolve conflicts between posts with numeric slugs and date archive queries.
						$query = wp_resolve_numeric_slug_conflicts($query);

						// Do the query
						$query = new WP_Query($query);
						if (!empty($query->posts) && $query->is_singular)
							return $query->post->ID;
						else
							return 0;
					}
				}
				return 0;
			}


////////////////////////////////////////////

			public function _getStr_templateInclude($template) {
				$template_new = hiweb()->string()->getStr_ifEmpty(hiweb()->getVal_fromArr(hiweb()->wp()->getArr_postMeta(get_the_ID()), 'hiweb-cp-page-template'));
				if (!hiweb()->string()->isEmpty($template_new)) return locate_template($template_new);
				return $template;
			}

		}
	}