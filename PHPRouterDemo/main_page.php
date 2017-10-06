<h1>MAIN PAGE</h1>

<p><a href="<?= (new PHPRouterDemo\DemoNodeAction(1))->url() ?>">Node 1 - class action</a></p>
<p><a href="<?= (new PHPRouterDemo\DemoNodeAction(2))->url() ?>">Node 2 - class action</a></p>
<p><a href="<?= (new PHPRouterDemo\DemoTermAction(1))->url() ?>">Term 1 - process action</a></p>
<p><a href="<?= (new PHPRouterDemo\DemoMagicAction('demo_magic'))->url() ?>">Magic - process action</a></p>
