<h1>output type test</h1>

{hello}

{const 'TEST_JTPL_COMPILER_ASSIGN'}

* {mailto array("address"=>"me@domain.com")}

* {mailto array("address"=>"me@domain.com","encode"=>"javascript")}

* {mailto array("address"=>"me@domain.com","encode"=>"hex")}

* {mailto array("address"=>"me@domain.com","subject"=>"Hello to you!")}

* {mailto array("address"=>"me@domain.com","cc"=>"you@domain.com,they@domain.com")}

* {mailto array("address"=>"me@domain.com","extra"=>'class="mailto"')}


<ul>
{for $i = 1; $i < 10; $i++}
    <li>{$i}</li>
{if $i == '4'}{break}{/if}
{/for}
</ul>

{include 'assets/included.tpl'}

Counter 1 : {for $i=0;$i<5;$i++} {counter 'first'}{/for}

{counter_init 'second', 'aa', 1, 2}
Counter 2 :  {counter 'second'} {counter 'second'} {counter 'second'}

counter reset : {counter_reset 'second'} {counter 'second'} {counter 'second'}
