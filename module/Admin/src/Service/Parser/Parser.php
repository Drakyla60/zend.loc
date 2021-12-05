<?php

namespace Admin\Service\Parser;
use Admin\Entity\Post;
use Symfony\Component\DomCrawler\Crawler;

class Parser implements ParseInterface
{

    private $mongoManager;
    private $entityManager;

    public function __construct($mongoManager, $entityManager)
    {
        $this->mongoManager = $mongoManager;
        $this->entityManager = $entityManager;
    }

    const PARSE_URL = 'https://laravel.ru/posts';

    public function parse()
    {
        $pageParseUrl = [ self::PARSE_URL ];

        for ($i = 1;$i <= 35 ;$i++){
            $getPages[] = 'https://laravel.ru/posts?page=' . $i;
            $pageParseUrl = array_merge( $getPages);
        }

        $getPostsUrl = [];

        foreach ($pageParseUrl as $item) {
            $normalizeUrl = $this->createNormalizeUrl($item);

            $getContent = $this->getHtml($normalizeUrl);

            $crawler = new Crawler($getContent);

            $getPages = $crawler->filter('h2.hvl-h2 > a')
                ->each(function (Crawler $link) {
                    return $link->attr('href');
                });
            $getPostsUrl = array_merge($getPostsUrl, $getPages);

            if (count($getPostsUrl) == 100) {
                break;
            }
        }


        $array = [];
        foreach ($getPostsUrl as $item) {
            $getContentTopic = $this->getHtml($item);
            $crawlerTopic = new Crawler($getContentTopic);

            $post = $crawlerTopic
                ->filter('div.hvl-content')
                ->each(function (Crawler $crawler) {

                    $tagsCount = $crawler->filter('p.hvl-tags > a')->count();
                    $tags = [];
                    if (0 < $tagsCount) {
                        for ($i = 1; $i <= $tagsCount; $i++) {
                            $tags[] = $crawler->filter('p.hvl-tags > a:nth-of-type('. $i .')')->text();
                            $tags = array_merge($tags);
                        }
                    }
                    return  [
                        'postTitle' => $crawler->filter('h1.hvl-h1')->text(),
                        'postAuthor' => $crawler->filter('p.hvl-post-author > span > a > i')->text(),
                        'postDateCreated' => $crawler->filter('p.hvl-post-author > time')->attr('datetime'),
                        'postTags' => $tags,
                        'postContent' => $crawler->filter('article.hvl-markedup')->text(),
                        'postRating' => $crawler->filter('span.hvl-post-footer-ctl > b')->text(),
                        'postViews' => $crawler->filter('span.hvl-post-footer-ctl:nth-of-type(2)')->text(),
                    ];
                });

            $array = array_merge($array, $post);
        }

        foreach ($array as $item) {
            $post = new Post();
            $post->setTitle($item['postTitle']);
            $post->setAuthor($item['postAuthor']);
            $post->setDescription(iconv("UTF-8","UTF-8//IGNORE",substr($item['postContent'], 0, 200)));
            $post->setContent(iconv("UTF-8","UTF-8//IGNORE",$item['postContent']));
            $post->setTags(serialize($item['postTags']));
            $post->setRating($item['postRating']);
            $post->setViews($item['postViews']);

            $this->mongoManager->persist($post);

            $this->mongoManager->flush();
        }
    }


    /**
     * @return mixed|void
     */
    public function import()
    {
        // TODO: Implement import() method.
    }

    /**
     * Нормалізація адреси $url
     * @param $baseUrl
     * @return string
     */
    private function createNormalizeUrl($baseUrl)
    {
        return  ltrim($baseUrl, './');
    }

    /**
     * Парсинг сторінки за $url
     * @param $url
     * @return false|string
     */
    private function getHtml($url)
    {
        return file_get_contents($url);
    }

    //Паралельний парсинг
//    private function parallel_map(callable $func, array $items)
//    {
//        $childPids = [];
//        $result = [];
//        foreach ($items as $i => $item) {
//            $newPid = pcntl_fork();
//            if ($newPid == -1) {
//                die('Can\'t fork process');
//            } elseif ($newPid) {
//                $childPids[] = $newPid;
//                if ($i == count($items) - 1) {
//
//                    foreach ($childPids as $childPid) {
//                        pcntl_waitpid($childPid, $status);
//                        $sharedId = shmop_open($childPid, 'a', 0, 0);
//                        $shareData = shmop_read($sharedId, 0, shmop_size($sharedId));
//                        $result[] = unserialize($shareData);
//                        shmop_delete($sharedId);
//                        shmop_close($sharedId);
//                    }
//                }
//            } else {
//                $myPid = getmypid();
//                echo 'Start ' . $myPid . PHP_EOL;
//                $funcResult = $func($item);
//                $shareData = serialize($funcResult);
//                $sharedId = shmop_open($myPid, 'c', 0644, strlen($shareData));
//                shmop_write($sharedId, $shareData, 0);
//                echo 'Done ' . $myPid . ' ' . memory_get_peak_usage() . PHP_EOL;
////            exit(0);
//                posix_kill(getmypid(), SIGKILL);
//            }
//        }
//        return $result;
//    }
}