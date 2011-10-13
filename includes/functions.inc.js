function() { 
2	    var Dom = YAHOO.util.Dom, 
3	        Event = YAHOO.util.Event, 
4	        cal1, 
5	        over_cal = false, 
6	        cur_field = ''; 
7	 
8	    var init = function() { 
9	        cal1 = new YAHOO.widget.Calendar("cal1","cal1Container"); 
10	        cal1.selectEvent.subscribe(getDate, cal1, true); 
11	        cal1.renderEvent.subscribe(setupListeners, cal1, true); 
12	        Event.addListener(['cal1Date1', 'cal1Date2', 'cal1Date3'], 'focus', showCal); 
13	        Event.addListener(['cal1Date1', 'cal1Date2', 'cal1Date3'], 'blur', hideCal); 
14	        cal1.render(); 
15	        dp.SyntaxHighlighter.HighlightAll('code');  
16	    } 
17	 
18	    var setupListeners = function() { 
19	        Event.addListener('cal1Container', 'mouseover', function() { 
20	            over_cal = true; 
21	        }); 
22	        Event.addListener('cal1Container', 'mouseout', function() { 
23	            over_cal = false; 
24	        }); 
25	    } 
26	 
27	    var getDate = function() { 
28	            var calDate = this.getSelectedDates()[0]; 
29	            calDate = (calDate.getMonth() + 1) + '/' + calDate.getDate() + '/' + calDate.getFullYear(); 
30	            cur_field.value = calDate;             
31	            over_cal = false; 
32	            hideCal(); 
33	    } 
34	 
35	    var showCal = function(ev) { 
36	        var tar = Event.getTarget(ev); 
37	        cur_field = tar; 
38	     
39	        var xy = Dom.getXY(tar), 
40	            date = Dom.get(tar).value; 
41	        if (date) { 
42	            cal1.cfg.setProperty('selected', date); 
43	            cal1.cfg.setProperty('pagedate', new Date(date), true); 
44	        } else { 
45	            cal1.cfg.setProperty('selected', ''); 
46	            cal1.cfg.setProperty('pagedate', new Date(), true); 
47	        } 
48	        cal1.render(); 
49	        Dom.setStyle('cal1Container', 'display', 'block'); 
50	        xy[1] = xy[1] + 20; 
51	        Dom.setXY('cal1Container', xy); 
52	    } 
53	 
54	    var hideCal = function() { 
55	        if (!over_cal) { 
56	            Dom.setStyle('cal1Container', 'display', 'none'); 
57	        } 
58	    } 
59	 
60	    Event.addListener(window, 'load', init); 
61	 
62	}(); // JavaScript Document