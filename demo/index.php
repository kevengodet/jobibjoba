<?php

use Keven\JobiJoba\ApiClient;

$page = null;
if ($_GET['what'] ?? null && $_GET['where'] ?? null) {
    require_once dirname(__DIR__).'/vendor/autoload.php';
    require_once __DIR__.'/Pagination.php';
    require_once __DIR__.'/Uri.php';

    $client = new ApiClient(getenv('JOBIJOBA_CLIENT_ID'), getenv('JOBIJOBA_CLIENT_SECRET'));
    $page = $client->search(
            what: $_GET['what'] ?? '',
            where: $_GET['where'] ?? 'France',
            page: $_GET['page'] ?? null
    );
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://unpkg.com/axist@latest/dist/axist.min.css">
    <title>Jobijoba - Ad search engine</title>
</head>
<body>
    <header>
        <h1>Jobijoba</h1>
        <form action="index.php" method="get">
            <label>
                What?
                <input type=text placeholder="Développeur, Plaquiste, ..." value="<?=$_GET['what'] ?? ''?>" name=what>
            </label>
            <label>
                Where?
                <input type=text placeholder="Lyon, 38, Occitanie, ..." value="<?=$_GET['where'] ?? ''?>" name=where>
            </label>
            <input type="submit" value="Search">
        </form>
    </header>
    <?php if (!is_null($page)): ?>
    <?php if (count($page->jobs) === 0): ?>
            <article><h2>No job found.</h2></article>
    <?php else:?>
            <section><h3><?=count($page->jobs)?> jobs found.</h3></section>
    <article>
        <?php foreach ($page->jobs as $job): ?>
        <section>
            <p>
                <b><?=$job->title?></b>, <?=$job->company?> - <?=$job->city?> (<?=$job->department?>)
                <br /><small><?=str_replace("\n", '<br />', $job->description)?>&#8230;</small>
                <br /><a href=<?=$job->link?>>Postuler</a>
            </p>
        </section>
        <?php endforeach ?>
    </article>
    <?php endif ?>
    <?=(new Pagination($page))->generate()?>
    <?php endif ?>
</body>
</html>
