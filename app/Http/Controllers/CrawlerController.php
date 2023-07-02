<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Observers\CustomCrawlerObserver;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Spatie\Crawler\Crawler;
use App\Http\Controllers\Controller;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\DB;

class CrawlerController extends Controller
{
    public function __construct() {}  
    
    /**
     * Crawl the website content.
     * @return true
     */
    public function fetchContent(){

        $urls = ['https://www.vetropack.com/en', 'https://www.kniha-jizd-zdarma.cz', 'https://www.logbookie.eu', 
            'https://www.crm-zdarma.cz/cs', 'https://www.cez.cz', 'https://igloonet.cz'];

        foreach($urls as $url)
        {
            //# initiate crawler 
            Crawler::create([RequestOptions::ALLOW_REDIRECTS => true, RequestOptions::TIMEOUT => 30, RequestOptions::CONNECT_TIMEOUT => 10, RequestOptions::READ_TIMEOUT => 10])
            ->acceptNofollowLinks()
            ->ignoreRobots()
            // ->setParseableMimeTypes(['text/html', 'text/plain'])
            ->setCrawlObserver(new CustomCrawlerObserver())
            ->setCrawlProfile(new CrawlInternalUrls($url))
            ->setMaximumResponseSize(1024 * 1024 * 2) // 2 MB maximum
            ->setTotalCrawlLimit(100) // limit defines the maximal count of URLs to crawl
            // ->setConcurrency(1) // all urls will be crawled one by one
            ->setParseableMimeTypes(['text/html', 'text/plain'])
            ->setDelayBetweenRequests(100)
            ->startCrawling($url);
        }

        return redirect()->route('crawl-list');
    }

    public function displayContent()
    {
        try {
            //# prepare the data for insertion.
            DB::beginTransaction();
            $results = DB::table('web_text')->paginate(10);
            DB::commit();
            
            return view('table', compact('results'));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(['error'=>$e->getMessage()]);
        }

        
        //return false;
    }
}
