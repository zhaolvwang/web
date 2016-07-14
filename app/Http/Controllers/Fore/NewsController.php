<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use App\ArticleInfo;

class NewsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->filter = \DataFilter::source(ArticleInfo::orderby('id', 'desc'));
		$this->filter->build();
		
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->grid->add('title');
		$this->grid->add('descp');
		$this->grid->add('content|strip_tags|mb_substr[0,110]');
		$this->grid->add('created_at');
		$this->grid->add('updated_at');
		$this->grid->getGrid('rapyd::datagrid_custom_news');
		return $this->baseReturnView('fore.news', null, ArticleInfo::orderby('id', 'desc')->where('flag', 'like', '%推荐%')->get()->all()); 
	}
	
	public function getArticle($id) {
		$article = ArticleInfo::findOrNew($id);
		if ($article->exists) {
			$prev = $this->getPrevArticle($id);
			$next = $this->getNextArticle($id);
		}
		else {
			$article = null;
			$prev = null;
			$next = null;
		}
		return view('fore.article')
				->with('prev', $prev)
				->with('next', $next)
				->with('article', $article);
	}

	/**
	 * 取得上一篇的文章
	 * @param int $id
	 */
	protected function getPrevArticle($id)
	{
		return ArticleInfo::findOrNew(ArticleInfo::where('id', '<', $id)->max('id'));
	}


	/**
	 * 取得下一篇的文章
	 * @param int $id
	 */
	protected function getNextArticle($id)
	{
		return ArticleInfo::findOrNew(ArticleInfo::where('id', '>', $id)->min('id'));
	}
}
