# simple timetable php-based app (name subject to change)
**licence: *cc by-nc-sa 4.0***
## desc and storytime
like in title, app which is meant to be a simple school timetible for highschoolers. as you can see, i created it without any experience in real object programming and without practically any of programming good practics. maybe in future i'rewrite it (probably not), but at this time it is slightly difficult to understand code on-the-go. 
## main features:
* daily schedule with highlight of current lesson
* showing time to end of current lesson/next lesson (end of break)
* showing homeworks
	* for every subject on-click
	* for next day, by clicking on "button" under the schedule
* adding homeworks for predefined subjects
* three app's colouristics
## configuration:

* in file ```godziny.txt``` (‘hours.txt’ from polish) define hours of beginning and ending of your lessons separately hours and minutes in following lines. 

	for example your first 2 lessons' times are: 8:00-8:45 and 8:50-9:35, so you write something like:
	```
	8
	00
	8
	45
	8
	50
	9
	35
	```

* in files ```0.txt``` — ```6.txt``` define your schedule for every day. monday is ```1```, friday — ```5```. file ```0.txt``` is alert shown in free day. 

	scheme:
	``` 1Maths;37.0\ ```
	first digit stands for how many lessons in row of this subjects, next is name of subject, ``` ; ``` (first string separator), string for class' designation, ``` . ``` (second string separator), nr of group (``` 0 ``` — whole class, ``` 1 ``` — your group, ``` 2 ``` — some other group's lesson you want to be shown) and ending line with ``` \ ```.

* for using homework adding from browser, you need to define your password for modifying homework.txt. in every place in ```submit.php``` and ```pdedit.php``` where you can find ```/*your password*/``` comment, you put in your pass. 
* also, i think, you may want to set your own subjects, so in ```pd.html``` you need to edit ```<select>``` options, placing your own lessons' names. also, in file ```submit.php``` you need to change number of lessons in every ```for``` (i'm sorry).

**after this complicated procedure, you have almost maintenance-free timetable 🙃**
