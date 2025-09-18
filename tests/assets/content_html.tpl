<h1>output type test</h1>

{hello}

{const 'TEST_JTPL_COMPILER_ASSIGN'}


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

Cycle simple : {for $i=0;$i<5;$i++} {cycle array('a','e', 'i')}{/for}

{cycle_init array('c', 'd', 'f')}
Cycle default : {for $i=0;$i<5;$i++} {cycle}{/for}

{cycle_init 'foo', array('g', 'h', 'i')}
Cycle named : {for $i=0;$i<5;$i++} {cycle 'foo'}{/for}


{repeat_string 'mystring',4}


