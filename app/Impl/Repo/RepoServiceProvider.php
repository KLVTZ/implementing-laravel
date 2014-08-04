<?php namespace Impl\Repo;

use Tag;
use Article;
use Impl\Repo\Tag\EloquentTag;
use Impl\Service\Cache\LaravelCache;
use Impl\Repo\Article\CacheDecorator;
use Impl\Repo\Article\EloquentArticle;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider
	 *
	 * @return void
	 */
	public function register()
	{
		$app = $this->app;

		$app->bind('Impl\Repo\Article\ArticleInterface', function($app)
		{
			$article = new EloquentArticle(new Article,
				$app->make('Impl\Repo\Tag\TagInterface')
			);

			return new CacheDecorator($article, 
				new LaravelCache($app['cache'], 'articles', 10)
			);
		});

		$app->bind('Impl\Repo\Tag\TagInterface', function($app)
		{
			return new EloquentTag(
				new Tag,
				new LaravelCache($app['cache'], 'tags', 10)
			);
		});
	}
}
