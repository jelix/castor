
Json value: {$myjson|json_decode:"bar"}

datetime: {$mydate|datetime:'D, d M Y'}

nl2br: {$mytext|nl2br}

cat: {$mystring|cat:'ok'}

count_array: {$myarray|count_array}

count_paragraphs: {$mytext|count_paragraphs}

count_sentences: {$mytext|count_sentences}

count_words: {$mytext|count_words}

implode: {$myarray|implode:','}

indent:
{$mytext|indent:4}

number_format: {$mynumber|number_format:1:','}

replace: {$mystring|replace:'dolor':'bar'}

spacify: {$mystring|spacify}

sprintf: {$mydate|sprintf:'date is %s'}

strip: {$mystrip|strip}

truncathtml 30: {$myhtml|truncatehtml:30}

truncathtml 80: {$myhtml|truncatehtml:80}

wordwrap 30: {$mytext|wordwrap:20}

truncate 35: {$mytext|truncate:35}

regex_replace: {$mystring|regex_replace:'/(\w+) dolor/i':'abc $1 def'}

count_characters with spaces: {$myutf8string|count_characters:true}

count_characters without spaces: {$myutf8string|count_characters}
