var b0=new cl_view_game(); b0.prefix_cell ='b0-';
var b1=new cl_view_game(); b1.prefix_cell ='b1-'; b1.prefix2_cell ='b1-2-';
var b2=new cl_view_game(); b2.prefix_cell ='b2-';
var b3=new cl_view_game(); b3.prefix_cell ='b3-'; b3.prefix2_cell ='b3-2-';
var b4=new cl_view_game(); b4.prefix_cell ='b4-';
var b7=new cl_view_game(); b7.prefix_cell ='b7-';
var b8=new cl_view_game(); b8.prefix_cell ='b8-';
var b10=new cl_view_game(); b10.prefix_cell ='b10-';
var b11=new cl_view_game(); b11.prefix_cell ='b11-';
var b12=new cl_view_game(); b12.prefix_cell ='b12-';
var b13=new cl_view_game(); b13.prefix_cell ='b13-';
var b19=new cl_view_game(); b19.prefix_cell ='b19-';
var b21=new cl_view_game(); b21.prefix_cell ='b21-';
var b22=new cl_view_game(); b22.prefix_cell ='b22-';
var b23=new cl_view_game(); b23.prefix_cell ='b23-';
var b24=new cl_view_game(); b24.prefix_cell ='b24-';
var b25=new cl_view_game(); b25.prefix_cell ='b25-';
var b26=new cl_view_game(); b26.prefix_cell ='b26-';
var b27=new cl_view_game(); b27.prefix_cell ='b27-'; b27.prefix2_cell ='b27-2-';
var b32=new cl_view_game(); b32.prefix_cell ='b32-';
var b34=new cl_view_game(); b34.prefix_cell ='b34-';
var b37=new cl_view_game(); b37.prefix_cell ='b37-';
var b40=new cl_view_game(); b40.prefix_cell ='b40-';
var b52=new cl_view_game(); b52.prefix_cell ='b52-';
var b55=new cl_view_game(); b55.prefix_cell ='b55-';
var b006=new cl_view_game(); b006.prefix_cell ='b006-';
var b014=new cl_view_game(); b014.prefix_cell ='b014-';
var b016=new cl_view_game(); b016.prefix_cell ='b016-';
var b57=new cl_view_game(); b57.prefix_cell ='b57-';
var b58=new cl_view_game(); b58.prefix_cell ='b58-';
var b63=new cl_view_game(); b63.prefix_cell ='b63-';
var b64=new cl_view_game(); b64.prefix_cell ='b64-';
var b68=new cl_view_game(); b68.prefix_cell ='b68-';
var b69=new cl_view_game(); b69.prefix_cell ='b69-';
    
$(function(){
//Ãàìáèò Ýâàíñà, Ìîðôè - À.Ìîðôè (îòåö) Íîâûé Îðëåàí, âåñíà 1849
  if($("#board1").length > 0){
    b1.board_f =b1.begin_position();
    b1.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Ñf1-c4  Ñf8-c5,'+
                    '4. b2-b4   Ñc5-b4,   5. c2-c3   Ñb4-c5,   6. d2-d4   e5-d4,'+
                    '7. c3-d4   Ñc5-b6,   8. 0-0     Êc6-a5,   9. Ñc4-d3  Êg8-e7,'+
                   '10. Êb1-c3  0-0,     11. Ñc1-a3  d7-d6,   12. e4-e5   Ñc8-f5,'+
                   '13. e5-d6   c7-d6,   14. Êc3-e4  d6-d5,   15. Êe4-f6  g7-f6,'+
                   '16. Ña3-e7  Ôd8-e7,  17. Ñd3-f5  Êa5-c4,  18. Ëf1-e1  Ôe7-d6,'+
                   '19. Êf3-e5  f6-e5,   20. Ôd1-g4  Êðg8-h8, 21. Ôg4-h5  Êðh8-g7,'+
                   '22. Ôh5-g5  Êðg7-h8, 23. Ôg5-h5  h7-h6,   24. Ëe1-e5  Êc4-e5,'+
                   '25. d4-e5   Ôd6-c6,  26. e5-e6   Êðh8-g7, 27. g2-g4   Ôc6-c3,'+
                   '28. g4-g5   Ôc3-a1,  29. Êðg1-g2 Ôa1-f6,  30. g5-f6   Êðg7-f6,'+
                   '31. e6-f7   Ëf8-f7,  32. Ôh5-g6  Êðf6-e7, 33. Ôg6-e6  Êðe7-f8,'+
                   '34. Ôe6-h6  Ëf7-g7,  35. Ñf5-g6  Êðf8-g8, 36. h2-h4   d5-d4,'+
                   '37. h4-h5   d4-d3,   38. Ôh6-g5  Ëa8-d8,  39. h5-h6   d3-d2,'+
                   '40. Ôg5-f6  Ëg7-d7,  41. Ñg6-f5  d2-d1Ô,  42. h6-h7   Ëd7-h7,'+
                   '43. Ñf5-e6  Ëh7-f7,  44. Ñe6-f7  Êðg8-h7, 45. Ôf6-g6  Êðh7-h8,'+
                   '46. Ôg6-h6.';
    b1.make_moves(b1.parser_record_game());  
    b1.link_events('#b1-begin','#b1-prev','#b1-next','#b1-last');
    b1.link_events('#b1-2-begin','#b1-2-prev','#b1-2-next','#b1-2-last');
  }   
    
//Èòàëüÿíñêàÿ ïàðòèÿ, Ìîðôè - Ý.Ìîðôè Íîâûé Îðëåàí, 22 èþíÿ 1849
  if($("#board2").length > 0){
    b2.board_f =b2.begin_position();
    b2.record_game ='1. e2-e4  e7-e5,'+
                    '2. Êg1-f3 Êb8-c6,'+
                    '3. Ñf1-c4 Ñf8-c5,'+
                    '4. c2-c3  d7-d6,'+
                    '5. 0-0    Êg8-f6,'+
                    '6. d2-d4  e5-d4,'+
                    '7. c3-d4  Ñc5-b6,'+
                    '8. h2-h3  h7-h6,'+
                    '9. Êb1-c3 0-0,'+
                   '10. Ñc1-e3 Ëf8-e8,'+
                   '11. d4-d5  Ñb6-e3,'+
                   '12. d5-c6  Ñe3-b6,'+
                   '13. e4-e5  d6-e5,'+
                   '14. Ôd1-b3 Ëe8-e7,'+
                   '15. Ñc4-f7 Ëe7-f7,'+
                   '16. Êf3-e5 Ôd8-e8,'+
                   '17. c6-b7  Ñc8-b7,'+
                   '18. Ëa1-e1 Ñb7-a6,'+
                   '19. Êe5-g6 Ôe8-d8,'+
                   '20. Ëe1-e7.';
    b2.make_moves(b2.parser_record_game());  
    b2.link_events('#b2-begin','#b2-prev','#b2-next','#b2-last');
  }   

//Ðóññêàÿ ïàðòèÿ, Ìîðôè - Ëåâåíòàëü Íîâûé Îðëåàí, 22 ìàÿ 1850
  if($("#board3").length > 0){
    b3.board_f =b3.begin_position();
    b3.record_game ='1. e2-e4  e7-e5,'+
                    '2. Êg1-f3 Êg8-f6,'+
                    '3. Êf3-e5 d7-d6,'+
                    '4. Êe5-f3 Êf6-e4,'+
                    '5. Ôd1-e2 Ôd8-e7,'+
                    '6. d2-d3  Êe4-f6,'+
                    '7. Êb1-c3 Ñc8-e6,'+
                    '8. Ñc1-g5 h7-h6,'+
                    '9. Ñg5-f6 Ôe7-f6,'+
                   '10. d3-d4  c7-c6,'+
                   '11. 0-0-0  d6-d5,'+
                   '12. Êf3-e5 Ñf8-b4,'+
                   '13. Êc3-d5 Ñe6-d5,'+
                   '14. Êe5-g6 Ôf6-e6,'+
                   '15. Êg6-h8 Ôe6-e2,'+
                   '16. Ñf1-e2 Êðe8-f8,'+
                   '17. a2-a3  Ñb4-d6,'+
                   '18. Ñe2-d3 Êðf8-g8,'+
                   '19. Êh8-f7 Êðg8-f7,'+
                   '20. f2-f3  b7-b5,'+
                   '21. Ñd3-e4 Êb8-d7,'+
                   '22. Ëd1-e1 Êd7-f6,'+
                   '23. Ëe1-e2 Ëa8-e8,'+
                   '24. Ñe4-d5 c6-d5,'+
                   '25. Ëe2-e8 Êf6-e8,'+
                   '26. g2-g3  g7-g5,'+
                   '27. Êðc1-d2 Êe8-g7,'+
                   '28. Ëh1-a1 a7-a5,'+
                   '29. Êðd2-d3 Êðf7-e6,'+
                   '30. a3-a4  b5-b4,'+
                   '31. c2-c4  Ñd6-c7,'+
                   '32. Ëa1-e1 Êðe6-d6,'+
                   '33. Ëe1-e5 d5-c4,'+
                   '34. Êðd3-c4 Êg7-e6,'+
                   '35. Ëe5-b5 Êe6-f8,'+
                   '36. Ëb5-d5 Êðd6-e6,'+
                   '37. Ëd5-c5 Êðe6-d6,'+
                   '38. d4-d5  Êðd6-d7,'+
                   '39. Ëc5-c6 Ñc7-d6,'+
                   '40. Ëc6-a6 Êf8-g6,'+
                   '41. Ëa6-a5 Êg6-e5,'+
                   '42. Êðc4-b5 b4-b3,'+
                   '43. Ëa5-a7 Êðd7-d8,'+
                   '44. f3-f4  g5-f4,'+
                   '45. g3-f4 Êe5-d3,'+
                   '46. Êðb5-c4 Êd3-f4,'+
                   '47. Ëa7-h7 Ñd6-e5,'+
                   '48. Ëh7-h6 Ñe5-b2,'+
                   '49. Êðc4-b3 Ñb2-g7,'+
                   '50. Ëh6-h7 Ñg7-e5,'+
                   '51. a4-a5  Êf4-d5,'+
                   '52. Ëh7-h5 Ñe5-h2,'+
                   '53. Ëh5-d5 Êðd8-c8,'+
                   '54. Ëd5-b5 Êðc8-c7,'+
                   '55. Êðb3-c4 Êðc7-c6,'+
                   '56. Ëb5-h5 Ñh2-g1,'+
                   '57. Ëh5-h6 Êðc6-c7,'+
                   '58. Êðc4-b5 Êðc7-b8.';
    b3.make_moves(b3.parser_record_game());  
    b3.link_events('#b3-begin','#b3-prev','#b3-next','#b3-last');
    b3.link_events('#b3-2-begin','#b3-2-prev','#b3-2-next','#b3-2-last');
  }  
  
//Ñèöèëèàíñêàÿ ïàðòèÿ, Ìîðôè - Ëåâåíòàëü Íîâûé Îðëåàí, 22 ìàÿ 1850
  if($("#board4").length > 0){
    b4.board_f =b4.begin_position();
    b4.record_game ='1. e2-e4  c7-c5,'+
                    '2. f2-f4  e7-e6,'+
                    '3. Êg1-f3 d7-d5,'+
                    '4. e4-d5  e6-d5,'+
                    '5. d2-d4  Ñc8-g4,'+
                    '6. Ñf1-e2 Ñg4-f3,'+
                    '7. Ñe2-f3 Êg8-f6,'+
                    '8. 0-0    Ñf8-e7,'+
                    '9. Ñc1-e3 c5-d4,'+
                   '10. Ñe3-d4 0-0,'+
                   '11. Êb1-c3 Êb8-c6,'+
                   '12. Ñd4-f6 Ñe7-f6,'+
                   '13. Êc3-d5 Ñf6-b2,'+
                   '14. Ëa1-b1 Ñb2-d4,'+
                   '15. Êðg1-h1 Ëa8-b8,'+
                   '16. c2-c3  Ñd4-c5,'+
                   '17. f4-f5  Ôd8-h4,'+
                   '18. g2-g3  Ôh4-g5,'+
                   '19. f5-f6  Êc6-e5,'+
                   '20. f6-g7  Ëf8-d8,'+
                   '21. Ñf3-e4 Ôg5-g7,'+
                   '22. Ôd1-h5 Ëd8-d6,'+
                   '23. Ñe4-h7 Êðg8-f8,'+
                   '24. Ñh7-e4 Ëd6-h6,'+
                   '25. Ôh5-f5 Ôg7-g3,'+
                   '26. Ëb1-b2 Ëb8-e8,'+
                   '27. Êd5-f6 Ëe8-e6,'+
                   '28. Ëb2-g2 Ôg3-g2,'+
                   '29. Ñe4-g2 Ëh6-f6,'+
                   '30. Ôf5-f6 Ëe6-f6,'+
                   '31. Ëf1-f6 Êe5-g4,'+
                   '32. Ëf6-f5 b7-b6,'+
                   '33. Ñg2-d5 Êg4-h6,'+
                   '34. Ëf5-f6 Êðf8-g7,'+
                   '35. Ëf6-c6 a7-a5,'+
                   '36. Ëc6-c7 Êðg7-g6,'+
                   '37. Êðh1-g2 f7-f6,'+
                   '38. Êðg2-f3 Êh6-f5,'+
                   '39. Ñd5-e4 Êðg6-g5,'+
                   '40. Ñe4-f5 Êðg5-f5,'+
                   '41. h2-h4  Êðf5-g6,'+
                   '42. Ëc7-c6 Êðg6-h5,'+
                   '43. Êðf3-g3 f6-f5,'+
                   '44. Ëc6-f6 f5-f4,'+
                   '45. Êðg3-f4.';
    b4.make_moves(b4.parser_record_game());  
    b4.link_events('#b4-begin','#b4-prev','#b4-next','#b4-last');
  }  

//Ãàìáèò Êîíÿ, Ìîðôè - Íàéò Íîâûé Îðëåàí, 1856. Áåëûå áåç ëàäüè íà a1 è êîíÿ íà b1
  if($("#board7").length > 0){
    b7.board_f =b7.begin_position(); 
    b7.board_f['A'][1] ='';
    b7.board_f['B'][1] ='';
    b7.record_game ='1. e2-e4   e7-e5,   2. f2-f4  e5-f4,   3. Êg1-f3 g7-g5,'+
                    '4. Ñf1-c4  Ôd8-e7,  5. d2-d4  d7-d5,   6. Ñc4-d5 c7-c6,'+
                    '7. Ñd5-f7  Ôe7-f7,  8. Êf3-e5 Ôf7-f6,  9. Ôd1-h5 Êðe8-e7,'+
                   '10. h2-h4   g5-h4,  11. 0-0    Ñf8-h6, 12. b2-b3  Êb8-d7,'+
                   '13. Ñc1-a3  c6-c5,  14. Ëf1-d1 Êd7-e5, 15. Ña3-c5 Êðe7-e6,'+
                   '16. Ôh5-e8  Êg8-e7, 17. d4-d5.';
    b7.make_moves(b7.parser_record_game());  
    b7.link_events('#b7-begin','#b7-prev','#b7-next','#b7-last');
  }  

//Ãàìáèò Ñëîíà, Ìîðôè - Ìîðèàí, 1855. Áåëûå áåç ëàäüè íà a1
  if($("#board0").length > 0){
    b0.board_f =b0.begin_position(); 
    b0.board_f['A'][1] ='';
    b0.record_game ='1. e2-e4   e7-e5,   2. f2-f4  e5-f4,   3. Ñf1-c4  Ôd8-h4,'+
                    '4. Êðe1-f1 b7-b5,   5. Ñc4-d5 Êb8-c6,  6. Êg1-f3  Ôh4-h5,'+
                    '7. d2-d4   Êg8-f6,  8. Ñd5-b3 Ñc8-a6,  9. Ôd1-e2  Êc6-d4,'+
                   '10. Êf3-d4  b5-b4,  11. Ôe2-a6 Ôh5-d1, 12. Êðf1-f2 Êf6-g4.';
    b0.make_moves(b0.parser_record_game());  
    b0.link_events('#b0-begin','#b0-prev','#b0-next','#b0-last');
  }  

//Èòàëüÿíñêàÿ ïàðòèÿ, Òîìïñîí Ìîðôè 8 îêòÿáðÿ 1857 ãîäà Íüþ-Éîðê
  if($("#board8").length > 0){
    b8.board_f =b8.begin_position(); 
    b8.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Ñf1-c4  Ñf8-c5,'+
                    '4. c2-c3   Êg8-f6,   5. d2-d3   d7-d6,    6. h2-h3   Ñc8-e6,'+
                    '7. Ñc4-b3  d6-d5,    8. e4-d5   Ñe6-d5,   9. 0-0     0-0,'+
                   '10. Ñc1-g5  Ñd5-b3,  11. a2-b3   h7-h6,   12. Ñg5-h4  g7-g5,'+
                   '13. Ñh4-g3  e5-e4,   14. Êf3-e5  Êc6-e5,  15. Ñg3-e5  e4-d3,'+
                   '16. Ñe5-f6  Ôd8-f6,  17. Ôd1-d3  Ëa8-d8,  18. Ôd3-c2  Ëf8-e8,'+
                   '19. b3-b4   Ñc5-b6,  20. Êb1-a3  Ôf6-f4,  21. Ëa1-d1  c7-c6,'+
                   '22. Ëd1-d3  Ñb6-f2,  23. Êðg1-h1 Ëd8-d3,  24. Ôc2-d3  Ëe8-e3,'+
                   '25. Ôd3-d8  Êðg8-g7, 26. Ôd8-d4  Ôf4-d4,  27. c3-d4   Ëe3-e2,'+
                   '28. Êa3-c4  Ëe2-e1,  29. Ëf1-e1  Ñf2-e1,  30. Êc4-a5  Ñe1-b4,'+
                   '31. Êa5-b7  Êðg7-f6, 32. Êb7-d8  c6-c5,   33. Êd8-c6  Êðf6-e6,'+
                   '34. d4-c5   Ñb4-c5,  35. g2-g4   Êðe6-d5, 36. Êc6-d8  f7-f6,'+
                   '37. Êðh1-g2 a7-a5,   38. Êðg2-f3 a5-a4,   39. Êðf3-e2 Ñc5-d4,'+
                   '40. Êðe2-d3 Ñd4-b2,  41. Êd8-f7  Ñb2-e5,  42. Êðd3-c2 Êðd5-c4,'+
                   '43. Êf7-d8  a4-a3,   44. Êd8-b7  a3-a2,   45. Êb7-a5  Êðc4-b4,'+
                   '46. Êa5-b3  Êðb4-a3.';
    b8.make_moves(b8.parser_record_game());  
    b8.link_events('#b8-begin','#b8-prev','#b8-next','#b8-last');
  }  
  
//Øîòëàíäñêèé ãàìáèò Ëèõòåíãåéí Ìîðôè 22 îêòÿáðÿ 1857 ãîäà Íüþ-Éîðê
  if($("#board10").length > 0){
    b10.board_f =b10.begin_position(); 
    b10.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. d2-d4  e5-d4,'+
                     '4. Ñf1-c4  Êg8-f6,   5. e4-e5   d7-d5,    6. Ñc4-b5 Êf6-e4,'+
                     '7. Êf3-d4  Ñc8-d7,   8. Êd4-c6  b7-c6,    9. Ñb5-d3 Ñf8-c5,'+
                    '10. Ñd3-e4  Ôd8-h4,  11. Ôd1-e2  d5-e4,   12. Ñc1-e3 Ñd7-g4,'+
                    '13. Ôe2-c4  Ñc5-e3,  14. g2-g3   Ôh4-d8,  15. f2-e3  Ôd8-d1,'+
                    '16. Êðe1-f2 Ôd1-f3,  17. Êðf2-g1 Ñg4-h3,  18. Ôc4-c6 Êðe8-f8,'+
                    '19. Ôc6-a8  Êðf8-e7.';
    b10.make_moves(b10.parser_record_game());  
    b10.link_events('#b10-begin','#b10-prev','#b10-next','#b10-last');
  }  
  
//Ðóññêàÿ ïàðòèÿ Ìîðôè Ëèõòåíãåéí 23 îêòÿáðÿ 1857 ãîäà Íüþ-Éîðê
  if($("#board11").length > 0){
    b11.board_f =b11.begin_position(); 
    b11.record_game ='1. e2-e4   e7-e5,    2. Ñf1-c4  Êg8-f6,   3. Êg1-f3  Êf6-e4,'+
                     '4. Êb1-c3  d7-d5,    5. Ñc4-d5  Êe4-f6,   6. Ñd5-b3  Ñf8-d6,'+
                     '7. d2-d3   0-0,      8. h2-h3   h7-h6,    9. Ñc1-e3  Êb8-c6,'+
                    '10. Ôd1-d2  Êc6-a5,  11. g2-g4   Êa5-b3,  12. a2-b3   Ñc8-d7,'+
                    '13. Ëh1-g1  Êf6-h7,  14. Êc3-e4  Êðg8-h8, 15. g4-g5   h6-h5,'+
                    '16. Êf3-h4  g7-g6,   17. Ôd2-e2  Ñd7-c6,  18. f2-f4   e5-f4,'+
                    '19. Ñe3-d4  Êðh8-g8, 20. Êh4-f5  Ëf8-e8,  21. Êf5-h6  Êðg8-f8,'+
                    '22. 0-0-0   Ñc6-e4,  23. d3-e4   Ôd8-e7,  24. e4-e5   Ñd6-e5,'+
                    '25. Ñd4-e5  Ôe7-e5,  26. Ëd1-d7  Ôe5-g7,  27. Ôe2-c4  Ëe8-e7,'+
                    '28. Ëd7-e7  Êðf8-e7, 29. Ëg1-e1.';
    b11.make_moves(b11.parser_record_game());  
    b11.link_events('#b11-begin','#b11-prev','#b11-next','#b11-last');
  }  

//Äåáþò ÷åòûðåõ êîíåé Ïàóëüñåí Ìîðôè 8 íîÿáðÿ 1857 ãîäà Íüþ-Éîðê
  if($("#board12").length > 0){
    b12.board_f =b12.begin_position(); 
    b12.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Êb1-c3  Êg8-f6,'+
                     '4. Ñf1-b5  Ñf8-c5,   5. 0-0     0-0,      6. Êf3-e5  Ëf8-e8,'+
                     '7. Êe5-c6  d7-c6,    8. Ñb5-c4  b7-b5,    9. Ñc4-e2  Êf6-e4,'+
                    '10. Êc3-e4  Ëe8-e4,  11. Ñe2-f3  Ëe4-e6,  12. c2-c3   Ôd8-d3,'+
                    '13. b2-b4   Ñc5-b6,  14. a2-a4   b5-a4,   15. Ôd1-a4  Ñc8-d7,'+
                    '16. Ëa1-a2  Ëa8-e8,  17. Ôa4-a6  Ôd3-f3,  18. g2-f3   Ëe6-g6,'+
                    '19. Êðg1-h1 Ñd7-h3,  20. Ëf1-d1  Ñh3-g2,  21. Êðh1-g1 Ñg2-f3,'+
                    '22. Êðg1-f1 Ñf3-g2,  23. Êðf1-g1 Ñg2-h3,  24. Êðg1-h1 Ñb6-f2,'+
                    '25. Ôa6-f1  Ñh3-f1,  26. Ëd1-f1  Ëe8-e2,  27. Ëa2-a1  Ëg6-h6,'+
                    '28. d2-d4   Ñf2-e3.';
    b12.make_moves(b12.parser_record_game());  
    b12.link_events('#b12-begin','#b12-prev','#b12-next','#b12-last');
  }  

//Ãàìáèò Ýâàíñà Ìîðôè - Ñòýíëè, Íüþ-Éîðê, îñåíü 1857 ãîäà
  if($("#board13").length > 0){
    b13.board_f =b13.begin_position(); 
    b13.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Ñf1-c4  Ñf8-c5,'+
                     '4. b2-b4   Ñc5-b4,   5. c2-c3   Ñb4-a5,   6. d2-d4   e5-d4,'+
                     '7. 0-0     d7-d6,    8. c3-d4   Ña5-b6,   9. Êb1-c3  Êg8-f6,'+
                    '10. e4-e5   d6-e5,   11. Ñc1-a3  Ñb6-d4,  12. Ôd1-b3  Ñc8-e6,'+
                    '13. Ñc4-e6  f7-e6,   14. Ôb3-e6  Êc6-e7,  15. Êf3-d4  e5-d4,'+
                    '16. Ëf1-e1  Êf6-g8,  17. Êc3-d5  Ôd8-d7,  18. Ña3-e7  Ôd7-e6,'+
                    '19. Ëe1-e6  Êðe8-d7, 20. Ëa1-e1  Ëa8-e8,  21. Ëe6-e4  c7-c6,'+
                    '22. Ëe4-d4  c6-d5,   23. Ëd4-d5  Êðd7-c6, 24. Ëd5-d6  Êðc6-c7,'+
                    '25. Ëe1-c1  Êðc7-b8, 26. Ñe7-h4  Êg8-h6,  27. Ñh4-g3  Êðb8-a8,'+
                    '28. h2-h3   Êh6-f5,  29. Ëd6-d7  g7-g6,   30. Ëc1-c7  Êf5-g3,'+
                    '31. f2-g3   Ëe8-b8,  32. Ëd7-h7  Ëh8-h7,  33. Ëc7-h7  a7-a5,'+
                    '34. h3-h4   Ëb8-g8,  35. g3-g4   b7-b5,   36. h4-h5   a5-a4,'+
                    '37. h5-h6   b5-b4,   38. Ëh7-g7  Ëg8-h8,  39. h6-h7   b4-b3,'+
                    '40. Ëg7-g8  Êða8-b7, 41. Ëg8-h8  b3-b2,   42. Ëh8-b8  Êðb7-b8,'+
                    '43. h7-h8Ô.';
    b13.make_moves(b13.parser_record_game());  
    b13.link_events('#b13-begin','#b13-prev','#b13-next','#b13-last');
  }  

//Çàùèòà Ôèëèäîðà Ñòàóíòîí+Îóýí - Ìîðôè+Áåðíñ, Àíãëèÿ, ëåòî 1858 ãîäà
  if($("#board19").length > 0){
    b19.board_f =b19.begin_position(); 
    b19.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  d7-d6,    3. d2-d4   f7-f5,'+
                     '4. d4-e5   f5-e4,    5. Êf3-g5  d6-d5,    6. e5-e6   Êg8-h6,'+
                     '7. Êb1-c3  c7-c6,    8. Êg5-e4  d5-e4,    9. Ôd1-h5  g7-g6,'+
                    '10. Ôh5-e5  Ëh8-g8,  11. Ñc1-h6  Ñf8-h6,  12. Ëa1-d1  Ôd8-g5,'+
                    '13. Ôe5-c7  Ñc8-e6,  14. Ôc7-b7  e4-e3,   15. f2-f3   Ôg5-e7,'+
                    '16. Ôb7-a8  Êðe8-f7, 17. Êc3-e4  Ñh6-f4,  18. Ñf1-e2  Êðf7-g7,'+
                    '19. 0-0     Ôe7-c7,  20. Êe4-c5  Ñf4-h2,  21. Êðg1-h1 Ñe6-c8,'+
                    '22. Ëd1-d4  Ñh2-g3,  23. Ëd4-e4  Êðg7-h8, 24. Ëf1-d1  Ôc7-g7,'+
                    '25. Ëe4-h4  Ñg3-h4,  26. Ôa8-b8  Ñc8-a6,  27. Ôb8-h2  Ña6-e2,'+
                    '28. Ëd1-d7  Ôg7-h6,  29. Êc5-e4  Ñe2-c4,  30. Êe4-f6  e3-e2,'+
                    '31. Ëd7-e7  Ôh6-c1,  32. Ôh2-g1  Ôc1-g1,  33. Êðh1-g1 e2-e1Ô,'+
                    '34. Ëe7-e1  Ñh4-e1.';
    b19.make_moves(b19.parser_record_game());  
    b19.link_events('#b19-begin','#b19-prev','#b19-next','#b19-last');
  }  

//Çàùèòà Ôèëèäîðà Áåðíñ - Ìîðôè, Àíãëèÿ, ëåòî 1858 ãîäà
  if($("#board21").length > 0){
    b21.board_f =b21.begin_position(); 
    b21.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  d7-d6,    3. d2-d4   f7-f5,'+
                     '4. d4-e5   f5-e4,    5. Êf3-g5  d6-d5,    6. e5-e6   Ñf8-c5,'+
                     '7. Êg5-f7  Ôd8-f6,   8. Ñc1-e3  d5-d4,    9. Ñe3-g5  Ôf6-f5,'+
                    '10. Êf7-h8  Ôf5-g5,  11. Ñf1-c4  Êb8-c6,  12. Êh8-f7  Ôg5-g2,'+
                    '13. Ëh1-f1  Êg8-f6,  14. f2-f3   Êc6-b4,  15. Êb1-a3  Ñc8-e6,'+
                    '16. Ñc4-e6  Êb4-d3,  17. Ôd1-d3  e4-d3,   18. 0-0-0   Ñc5-a3,'+
                    '19. Ñe6-b3  d3-d2,   20. Êðc1-b1 Ña3-c5,  21. Êf7-e5  Êðe8-f8,'+
                    '22. Êe5-d3  Ëa8-e8,  23. Êd3-c5  Ôg2-f1,  24. Êc5-e6  Ëe8-e6.';
    b21.make_moves(b21.parser_record_game());  
    b21.link_events('#b21-begin','#b21-prev','#b21-next','#b21-last');
  }  

//Çàùèòà Ôèëèäîðà Áåðíñ - Ìîðôè, Àíãëèÿ, ëåòî 1858 ãîäà
  if($("#board22").length > 0){
    b22.board_f =b22.begin_position(); 
    b22.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  d7-d6,    3. d2-d4   e5-d4,'+
                     '4. Êf3-d4  Êg8-f6,   5. Ñf1-d3  Ñf8-e7,   6. Êb1-c3  0-0,'+
                     '7. 0-0     c7-c5,    8. Êd4-e2  Êb8-c6,   9. f2-f4   a7-a6,'+
                    '10. a2-a4   Ñc8-g4,  11. h2-h3   Ñg4-e2,  12. Ôd1-e2  Ëf8-e8,'+
                    '13. Ôe2-f2  Ëa8-c8,  14. g2-g4   Êc6-b4,  15. b2-b3   d6-d5,'+
                    '16. Êðg1-h1 d5-e4,   17. Êc3-e4  Êf6-e4,  18. Ñd3-e4  Ñe7-f6,'+
                    '19. Ñe4-b7  Ñf6-a1,  20. Ñb7-c8  Ôd8-c8,  21. Ñc1-e3  Ôc8-c6,'+
                    '22. Êðh1-h2 Ña1-d4.';
    b22.make_moves(b22.parser_record_game());  
    b22.link_events('#b22-begin','#b22-prev','#b22-next','#b22-last');
  }  

//Îòêàçàííûé êîðîëåâñêèé ãàìáèò Ìîðôè - Áîäåí, Àíãëèÿ, ëåòî 1858 ãîäà
  if($("#board23").length > 0){
    b23.board_f =b23.begin_position(); 
    b23.record_game ='1. e2-e4   e7-e5,    2. f2-f4   Ñf8-c5,   3. Êg1-f3  d7-d6,'+
                     '4. c2-c3   Ñc8-g4,   5. Ñf1-e2  Êb8-c6,   6. b2-b4   Ñc5-b6,'+
                     '7. b4-b5   Êc6-a5,   8. d2-d4   Ñg4-f3,   9. Ñe2-f3  e5-d4,'+
                    '10. c3-d4   Ôd8-f6,  11. Ñc1-e3  Êa5-c4,  12. Ñe3-f2  Ôf6-f4,'+
                    '13. 0-0     Êg8-f6,  14. Ôd1-d3  Êc4-a5,  15. Êb1-c3  0-0,'+
                    '16. g2-g3   Ôf4-h6,  17. Êðg1-g2 Ëa8-e8,  18. Ëa1-e1  Êðg8-h8,'+
                    '19. Ñf2-e3  Ôh6-g6,  20. Êc3-e2  h7-h6,   21. Ñe3-d2  d6-d5,'+
                    '22. Êe2-f4  Ôg6-h7,  23. e4-e5   Ôh7-d3,  24. Êf4-d3  Êa5-c4,'+
                    '25. Ñd2-b4  Êf6-e4,  26. Ñb4-f8  Ëe8-f8,  27. Êd3-f4  Êe4-d2,'+
                    '28. Ñf3-d5  Êd2-f1,  29. Ñd5-c4  Êf1-d2,  30. Ñc4-d5  Ñb6-d4,'+
                    '31. e5-e6   g7-g5,   32. e6-e7   Ëf8-e8,  33. Ñd5-f7  g5-f4,'+                    
                    '34. g3-f4   Ëe8-e7,  35. Ëe1-e7.';
    b23.make_moves(b23.parser_record_game());  
    b23.link_events('#b23-begin','#b23-prev','#b23-next','#b23-last');
  }  

//Çàùèòà Ôèëèäîðà Áåðä - Ìîðôè, Àíãëèÿ, ëåòî 1858 ãîäà
  if($("#board24").length > 0){
    b24.board_f =b24.begin_position(); 
    b24.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  d7-d6,    3. d2-d4   f7-f5,'+
                     '4. Êb1-c3  f5-e4,    5. Êc3-e4  d6-d5,    6. Êe4-g3  e5-e4,'+
                     '7. Êf3-e5  Êg8-f6,   8. Ñc1-g5  Ñf8-d6,   9. Êg3-h5  0-0,'+
                    '10. Ôd1-d2  Ôd8-e8,  11. g2-g4   Êf6-g4,  12. Êe5-g4  Ôe8-h5,'+
                    '13. Êg4-e5  Êb8-c6,  14. Ñf1-e2  Ôh5-h3,  15. Êe5-c6  b7-c6,'+
                    '16. Ñg5-e3  Ëa8-b8,  17. 0-0-0   Ëf8-f2,  18. Ñe3-f2  Ôh3-a3,'+
                    '19. c2-c3   Ôa3-a2,  20. b2-b4   Ôa2-a1,  21. Êðc1-c2 Ôa1-a4,'+
                    '22. Êðc2-b2 Ñd6-b4,  23. c3-b4   Ëb8-b4,  24. Ôd2-b4  Ôa4-b4,'+
                    '25. Êðb2-c2 e4-e3,   26. Ñf2-e3  Ñc8-f5,  27. Ëd1-d3  Ôb4-c4,'+
                    '28. Êðc2-d2 Ôc4-a2,  29. Êðd2-d1 Ôa2-b1.';
    b24.make_moves(b24.parser_record_game());  
    b24.link_events('#b24-begin','#b24-prev','#b24-next','#b24-last');
  }  

//Çàùèòà Ôèëèäîðà Ëåâåíòàëü - Ìîðôè, Àíãëèÿ, 19 èþëÿ 1858 ãîäà, ïåðâàÿ ïàðòèÿ ìàò÷à
  if($("#board25").length > 0){
    b25.board_f =b25.begin_position(); 
    b25.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  d7-d6,    3. d2-d4   e5-d4,'+
                     '4. Êf3-d4  Êg8-f6,   5. Êb1-c3  Ñf8-e7,   6. Ñf1-e2  0-0,'+
                     '7. 0-0     c7-c5,    8. Êd4-f3  Êb8-c6,   9. Ñc1-f4  Ñc8-e6,'+
                    '10. Ôd1-d2  d6-d5,   11. e4-d5   Êf6-d5,  12. Ëa1-d1  Êd5-f4,'+
                    '13. Ôd2-f4  Ôd8-a5,  14. Ñe2-d3  Ëa8-d8,  15. Êf3-g5  Ñe7-g5,'+
                    '16. Ôf4-g5  h7-h6,   17. Ôg5-h4  Êc6-d4,  18. a2-a3   Ëf8-e8,'+
                    '19. Ëf1-e1  Ôa5-b6,  20. Êc3-a4  Ôb6-a5,  21. Êa4-c3  f7-f5,'+
                    '22. Ëe1-e5  Ñe6-f7,  23. Ëd1-e1  Ôa5-b6,  24. Ëe5-e8  Ëd8-e8,'+
                    '25. Ëe1-e8  Ñf7-e8,  26. Ôh4-e7  Ñe8-f7,  27. Êc3-a4  Ôb6-a5,'+
                    '28. Êa4-c5  Ôa5-d2,  29. f2-f3   Êd4-c6,  30. Ôe7-e2  Ôd2-c1,'+
                    '31. Êðg1-f2 Ôc1-b2,  32. Ñd3-f5  Ôb2-a3,  33. Ôe2-b5  Ôa3-c3,'+
                    '34. Êc5-b3  Ôc3-f6,  35. Ôb5-b7  g7-g6,   36. Ôb7-c8  Êðg8-h7,'+
                    '37. Ñf5-d3  Êc6-e5,  38. Êb3-d2  Ôf6-h4,  39. Êðf2-f1 Ôh4-h2,'+
                    '40. Êd2-e4  Ôh2-h1,  41. Êðf1-f2 Ôh1-c1,  42. Ôc8-c3  Ôc1-f4,'+
                    '43. Êðf2-e2 h6-h5,   44. Êe4-f2  h5-h4,   45. Ôc3-d2  Ôf4-g3,'+
                    '46. Ôd2-e3  a7-a5,   47. Ôe3-e4  Ñf7-e6,  48. f3-f4   Êe5-d3,'+
                    '49. c2-d3   Ñe6-g4,  50. Êðe2-f1 Ñg4-f5,  51. Ôe4-e7  Êðh7-h6.';
    b25.make_moves(b25.parser_record_game());  
    b25.link_events('#b25-begin','#b25-prev','#b25-next','#b25-last');
  }  

//Îòêàçàííûé êîðîëåâñêèé ãàìáèò Ìîðôè - Ëåâåíòàëü, Àíãëèÿ, 20 èþëÿ 1858 ãîäà, âòîðàÿ ïàðòèÿ ìàò÷à
  if($("#board26").length > 0){
    b26.board_f =b26.begin_position(); 
    b26.record_game ='1. e2-e4   e7-e5,    2. f2-f4   Ñf8-c5,   3. Êg1-f3  d7-d6,'+
                     '4. c2-c3   Ñc8-g4,   5. Ñf1-c4  Ñg4-f3,   6. Ôd1-f3  Êg8-f6,'+
                     '7. b2-b4   Ñc5-b6,   8. d2-d3   Êb8-d7,   9. f4-f5   Ôd8-e7,'+
                    '10. g2-g4   h7-h6,   11. Êðe1-e2 c7-c6,   12. g4-g5   h6-g5,'+
                    '13. Ñc1-g5  d6-d5,   14. Ñc4-b3  Ôe7-d6,  15. Êb1-d2  a7-a5,'+
                    '16. b4-a5   Ëa8-a5,  17. h2-h4   Êf6-h5,  18. Êd2-f1  Êd7-c5,'+
                    '19. Ñb3-c2  Ëa5-b5,  20. Ñg5-c1  d5-e4,   21. d3-e4   Ëb5-b2,'+
                    '22. Ñc1-b2  Êh5-f4,  23. Êðe2-e1 Êc5-d3,  24. Ñc2-d3  Êf4-d3,'+
                    '25. Êðe1-d2 Êd3-b2,  26. Êðd2-c2 Ôd6-a3,  27. Êf1-d2  Ñb6-c7,'+
                    '28. Êd2-b1.';
    b26.make_moves(b26.parser_record_game());  
    b26.link_events('#b26-begin','#b26-prev','#b26-next','#b26-last');
  }  

//Ðóññêàÿ ïàðòèÿ Ëåâåíòàëü - Ìîðôè, Àíãëèÿ, 22 èþëÿ 1858 ãîäà, òðåòüÿ ïàðòèÿ ìàò÷à
  if($("#board27").length > 0){
    b27.board_f =b27.begin_position(); 
    b27.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êg8-f6,   3. Êf3-e5  d7-d6,'+
                     '4. Êe5-f3  Êf6-e4,   5. d2-d4   d6-d5,    6. Ñf1-d3  Ñf8-e7,'+
                     '7. 0-0     Êb8-c6,   8. Ëf1-e1  f7-f5,    9. c2-c4   Ñc8-e6,'+
                    '10. c4-d5   Ñe6-d5,  11. Êb1-c3  Êe4-c3,  12. b2-c3   0-0,'+
                    '13. Ñc1-f4  Ñe7-d6,  14. Ñf4-d6  Ôd8-d6,  15. Êf3-e5  Ëa8-e8,'+
                    '16. c3-c4   Ñd5-e6,  17. Êe5-c6  b7-c6,   18. Ñd3-f1  Ñe6-f7,'+
                    '19. Ôd1-d2  Ëe8-e1,  20. Ëa1-e1  Ëf8-d8,  21. Ôd2-a5  Ôd6-d4,'+
                    '22. Ôa5-c7  Ôd4-b6,  23. Ôc7-f4  g7-g6,   24. h2-h3   Ôb6-b2,'+
                    '25. Ôf4-c7  Ôb2-b6,  26. Ëe1-e7  Ëd8-d1,  27. Ôc7-c8  Ëd1-d8,'+
                    '28. Ôc8-c7  Ëd8-d1,  29. Ôc7-e5  Ôb6-b1,  30. Ôe5-e2  Êðg8-f8,'+
                    '31. Ëe7-e5  f5-f4,   32. f2-f3   Ôb1-c1,  33. h3-h4   h7-h6,'+
                    '34. c4-c5   Êðf8-g7, 35. Ëe5-e4  Ôc1-c5,  36. Êðg1-h2 Ôc5-c1,'+
                    '37. Êðh2-g1 Ëd1-d2,  38. Ôe2-a6  Ëd2-a2,  39. Ôa6-d3  Ëa2-d2,'+
                    '40. Ôd3-a6  Ëd2-d1,  41. g2-g3   f4-g3,   42. Êðg1-g2 Ôc1-c5,'+
                    '43. Êðg2-g3 Ôc5-g1,  44. Ñf1-g2  Ëd1-d2,  45. Ôa6-f1  Ôg1-f1,'+
                    '46. Ñg2-f1  Êðg7-f6, 47. Ñf1-c4  Ñf7-c4,  48. Ëe4-c4  Ëd2-d6,'+
                    '49. Êðg3-f4 Ëd6-e6,  50. Ëc4-d4  Êðf6-e7, 51. Ëd4-a4  Êðe7-d6,'+
                    '52. Ëa4-a7  c6-c5,   53. Ëa7-a1  c5-c4,   54. h4-h5   g6-h5,'+
                    '55. Êðf4-f5 Ëe6-e3,  56. Êðf5-f4 Ëe3-e8,  57. Ëa1-a6  Êðd6-d5,'+
                    '58. Ëa6-h6  c4-c3,   59. Ëh6-h5  Êðd5-d4, 60. Ëh5-h7  Ëe8-c8,'+
                    '61. Ëh7-d7  Êðd4-c4, 62. Êðf4-e3 Ëc8-e8,  63. Êðe3-f2 c3-c2,'+
                    '64. Ëd7-c7  Êðc4-d3, 65. Ëc7-d7  Êðd3-c3, 66. Ëd7-c7  Êðc3-d2,'+
                    '67. Ëc7-d7  Êðd2-c1, 68. Ëd7-b7  Ëe8-e5,  69. f3-f4   Ëe5-e4,'+
                    '70. Êðf2-f3 Ëe4-c4,  71. Ëb7-h7  Êðc1-d2, 72. Ëh7-h1  c2-c1Ô,'+
                    '73. Ëh1-c1  Ëc4-c1,  74. Êðf3-e4 Ëc1-e1,  75. Êðe4-d4 Êðd2-e2,'+
                    '76. f4-f5   Êðe2-f3, 77. Êðd4-d5 Êðf3-f4, 78. f5-f6   Êðf4-g5,'+
                    '79. f6-f7   Ëe1-f1,  80. Êðd5-e6 Êðg5-g6.';                    
    b27.make_moves(b27.parser_record_game());  
    b27.link_events('#b27-begin','#b27-prev','#b27-next','#b27-last');
    b27.link_events('#b27-2-begin','#b27-2-prev','#b27-2-next','#b27-2-last');    
  }  
  
//Ôðàíöóçñêàÿ ïàðòèÿ, Îóýí - Ìîðôè, ïÿòàÿ ïàðòèÿ, ÷åðíûå áåç ïåøêè f7
  if($("#board32").length > 0){
    b32.board_f =b32.begin_position(); 
    b32.board_f['F'][7] ='';
    b32.record_game ='1. e2-e4   e7-e6,    2. d2-d4   d7-d5,    3. Ñf1-d3  g7-g6,'+
                     '4. Êg1-f3  c7-c5,    5. c2-c3   Êb8-c6,   6. 0-0     Ôd8-b6,'+
                     '7. e4-d5   e6-d5,    8. Ëf1-e1  Ñf8-e7,   9. Êf3-g5  Êg8-f6,'+
                    '10. Êg5-h7  Ëh8-h7,  11. Ñd3-g6  Ëh7-f7,  12. Ñc1-g5  Ñc8-g4,'+
                    '13. Ôd1-c2  Êðe8-f8, 14. Ñg6-f7  Êðf8-f7, 15. h2-h3   Ñg4-h5,'+
                    '16. Ñg5-f6  Ñh5-g6,  17. Ôc2-e2  Ñe7-f6,  18. Ôe2-e6  Êðf7-g7,'+
                    '19. Ôe6-d7  Êðg7-h8, 20. Ôd7-d6  Êðh8-g7, 21. Êb1-d2  c5-d4,'+
                    '22. Êd2-f3  Êc6-e5,  23. Ôd6-a3  Êe5-f3,  24. g2-f3   d4-c3,'+
                    '25. b2-c3   Ëa8-g8,  26. Ëe1-e3  Êðg7-h8, 27. Êðg1-h1 d5-d4,'+
                    '28. c3-d4   Ñf6-d4,  29. Ëa1-e1  Ñd4-e3,  30. Ëe1-e3  Ôb6-b1,'+
                    '31. Êðh1-h2 Ñg6-e4,  32. Ôa3-c3  Ëg8-g7,  33. Ëe3-e1  Ôb1-b6,'+
                    '34. Ëe1-e3  Ôb6-d6,  35. Êðh2-h1 Ñe4-f3,  36. Ëe3-f3  Ôd6-d1,'+
                    '37. Êðh1-h2 Ôd1-g1.';                    
    b32.make_moves(b32.parser_record_game());  
    b32.link_events('#b32-begin','#b32-prev','#b32-next','#b32-last');
  }  
  
//Ñèöèëèàíñêàÿ ïàðòèÿ, Ìîðôè - Ýâåðè, ñåàíñ èãðû âñëåïóþ, Áèðìèíãåì, 1858
  if($("#board006").length > 0){
    b006.board_f =b006.begin_position(); 
    b006.record_game ='1. e2-e4   c7-c5,    2. d2-d4   c5-d4,    3. Êg1-f3  Êb8-c6,'+
                      '4. Êf3-d4  e7-e6,    5. Ñc1-e3  Êg8-f6,   6. Ñf1-d3  d7-d5,'+
                      '7. Êd4-c6  b7-c6,    8. e4-e5   Êf6-d7,   9. f2-f4   Ñc8-a6,'+
                     '10. 0-0     Ña6-d3,  11. Ôd1-d3  Ñf8-c5,  12. Êb1-d2  Ñc5-e3,'+
                     '13. Ôd3-e3  Ôd8-b6,  14. Ëa1-e1  0-0,     15. b2-b3   f7-f6,'+
                     '16. e5-f6   Ëf8-f6,  17. g2-g3   Ëa8-f8,  18. Êðg1-g2 Ôb6-e3,'+
                     '19. Ëe1-e3  g7-g6,   20. Ëf1-e1  e6-e5,   21. Ëe1-e2  e5-f4,'+
                     '22. Ëe3-e7  Ëf8-f7,  23. g3-f4   Ëf6-f4,  24. Ëe7-e8  Êðg8-g7,'+
                     '25. Ëe8-c8  Ëf4-f6,  26. Ëc8-c7  Êd7-f8,  27. Ëe2-e7  Ëf7-e7,'+
                     '28. Ëc7-e7  Ëf6-f7,  29. Ëe7-e8  Êf8-d7,  30. Êd2-f3  Ëf7-f8,'+
                     '31. Ëe8-e7  Ëf8-f7,  32. Ëe7-e8  Ëf7-f8.';                    
    b006.make_moves(b006.parser_record_game());  
    b006.link_events('#b006-begin','#b006-prev','#b006-next','#b006-last');
  }  
  
//Çàùèòà Ôèëèäîðà, Ìîðôè-Ãàððâèö, ÷åòâåðòàÿ ïàðòèÿ, Ïàðèæ 1858 ãîä
  if($("#board34").length > 0){
    b34.board_f =b34.begin_position(); 
    b34.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  d7-d6,    3. d2-d4   e5-d4,'+
                     '4. Ôd1-d4  Êb8-c6,   5. Ñf1-b5  Ñc8-d7,   6. Ñb5-c6  Ñd7-c6,'+
                     '7. Ñc1-g5  f7-f6,    8. Ñg5-h4  Êg8-h6,   9. Êb1-c3  Ôd8-d7,'+
                    '10. 0-0     Ñf8-e7,  11. Ëa1-d1  0-0,     12. Ôd4-c4  Ëf8-f7,'+
                    '13. Êf3-d4  Êh6-g4,  14. h2-h3   Êg4-e5,  15. Ôc4-e2  g7-g5,'+
                    '16. Ñh4-g3  Ëf7-g7,  17. Êd4-f5  Ëg7-g6,  18. f2-f4   g5-f4,'+
                    '19. Ëf1-f4  Êðg8-h8, 20. Ëf4-h4  Ñe7-f8,  21. Ñg3-e5  f6-e5,'+
                    '22. Ëd1-f1  Ôd7-e6,  23. Êc3-b5  Ôe6-g8,  24. Ëf1-f2  a7-a6,'+
                    '25. Êb5-c7  Ëa8-c8,  26. Êc7-d5  Ñc6-d5,  27. e4-d5   Ëc8-c7,'+
                    '28. c2-c4   Ñf8-e7,  29. Ëh4-h5  Ôg8-e8,  30. c4-c5   Ëc7-c5,'+
                    '31. Ëh5-h7  Êðh8-h7, 32. Ôe2-h5  Êðh7-g8, 33. Êf5-e7  Êðg8-g7,'+
                    '34. Êe7-f5  Êðg7-g8, 35. Êf5-d6.';                    
    b34.make_moves(b34.parser_record_game());  
    b34.link_events('#b34-begin','#b34-prev','#b34-next','#b34-last');
  }  
  
//Ãîëëàíäñêàÿ ïàðòèÿ, Ãàððâèö-Ìîðôè, ñåäüìàÿ ïàðòèÿ, Ïàðèæ 1858 ãîä
  if($("#board37").length > 0){
    b37.board_f =b37.begin_position(); 
    b37.record_game ='1. d2-d4   f7-f5,    2. c2-c4   e7-e6,    3. Êb1-c3  Êg8-f6,'+
                     '4. Ñc1-g5  Ñf8-e7,   5. e2-e3   0-0,      6. Ñf1-d3  b7-b6,'+
                     '7. Êg1-e2  Ñc8-b7,   8. Ñg5-f6  Ñe7-f6,   9. 0-0     Ôd8-e7,'+
                    '10. Ôd1-d2  d7-d6,   11. f2-f4   c7-c5,   12. d4-d5   Êb8-a6,'+
                    '13. d5-e6   Ôe7-e6,  14. Ëa1-e1  Ñf6-h4,  15. Êe2-g3  Ôe6-g6,'+
                    '16. Êc3-d5  Ñb7-d5,  17. c4-d5   Ñh4-g3,  18. h2-g3   Êa6-c7,'+
                    '19. Êðg1-f2 Ëa8-e8,  20. Ëf1-h1  Ëe8-e7,  21. Ëh1-h4  Ôg6-f7,'+
                    '22. Ñd3-e2  Êc7-e8,  23. Ôd2-d3  Êe8-f6,  24. Ñe2-f3  g7-g6,'+
                    '25. Ëe1-e2  Ëf8-e8,  26. b2-b3   Ôf7-g7,  27. Ëh4-h1  h7-h6,'+
                    '28. Êðf2-g1 g6-g5,   29. f4-g5   h6-g5,   30. Ñf3-h5  Êf6-e4,'+
                    '31. Ëe2-e1  Ëe8-f8,  32. Ñh5-f3  Êe4-g3,  33. Ëh1-h3  Ôg7-e5,'+
                    '34. Ëh3-h6  g5-g4,   35. Ñf3-d1  Êðg8-g7, 36. Ëh6-h4  Ëf8-h8,'+
                    '37. Ëh4-h8  Êðg7-h8, 38. Ñd1-c2  Ëe7-h7,  39. Ôd3-d2  Ôe5-b2,'+
                    '40. Ëe1-d1  Ëh7-h1,  41. Êðg1-f2 Ëh1-f1,  42. Êðf2-g3 Ôb2-e5,'+
                    '43. Êðg3-h4 Ôe5-f6,  44. Êðh4-g3 Ôf6-e5,  45. Êðg3-h4 Ôe5-f6.';                    
    b37.make_moves(b37.parser_record_game());  
    b37.link_events('#b37-begin','#b37-prev','#b37-next','#b37-last');
  }
  
//Èñïàíñêàÿ ïàðòèÿ, Ìîðôè - Àíäåðñåí, òðåòüÿ ïàðòèÿ, Ïàðèæ, 22 äåêàáðÿ 1858 ãîäà
  if($("#board40").length > 0){
    b40.board_f =b40.begin_position(); 
    b40.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Ñf1-b5  Êg8-f6,'+
                     '4. d2-d4   Êc6-d4,   5. Êf3-d4  e5-d4,    6. e4-e5   c7-c6,'+
                     '7. 0-0     c6-b5,    8. Ñc1-g5  Ñf8-e7,   9. e5-f6   Ñe7-f6,'+
                    '10. Ëf1-e1  Êðe8-f8, 11. Ñg5-f6  Ôd8-f6,  12. c2-c3   d7-d5,'+
                    '13. c3-d4   Ñc8-e6,  14. Êb1-c3  a7-a6,   15. Ëe1-e5  Ëa8-d8,'+
                    '16. Ôd1-b3  Ôf6-e7,  17. Ëa1-e1  g7-g5,   18. Ôb3-d1  Ôe7-f6,'+
                    '19. Ëe1-e3  Ëh8-g8,  20. Ëe5-e6.';
    b40.make_moves(b40.parser_record_game());  
    b40.link_events('#b40-begin','#b40-prev','#b40-next','#b40-last');
  }  
  
//Íåïðàâèëüíîå íà÷àëî, Ìîðôè - Ìîíãðåäüåí, øåñòàÿ ïàðòèÿ, Ïàðèæ, 1859 ãîä.
  if($("#board52").length > 0){
    b52.board_f =b52.begin_position(); 
    b52.record_game = '1.  e2-e4   e7-e5,   2. Êg1-f3  d7-d5,   3. e4-d5   e5-e4,'+
                      '4.  Ôd1-e2  Ôd8-e7,  5. Êf3-d4  Ôe7-e5,  6. Êd4-b5  Ñf8-d6,'+
                      '7.  d2-d4   Ôe5-e7,  8. c2-c4   Ñd6-b4,  9. Ñc1-d2  Ñb4-d2,'+
                      '10. Êb1-d2  a7-a6,  11. Êb5-c3  f7-f5,  12. 0-0-0   Êg8-f6,'+
                      '13. Ëd1-e1  0-0,    14. f2-f3   b7-b5,  15. f3-e4   f5-e4,'+
                      '16. Êd2-e4  b5-c4,  17. Ôe2-c4  Êðg8-h8,18. Ñf1-d3  Ñc8-b7,'+
                      '19. Êe4-f6  Ôe7-f6, 20. Ëh1-f1  Ôf6-d8, 21. Ëf1-f8  Ôd8-f8,'+
                      '22. Ôc4-b4.';
    b52.make_moves(b52.parser_record_game());  
    b52.link_events('#b52-begin','#b52-prev','#b52-next','#b52-last');
  }  

//Çàùèòà Ôèëèäîðà, Ìîðôè - Ãåðöîã Êàðë Áðàóíøâåéãñêèé+ãðàô Èçóàð, Ïàðèæ, 1858
  if($("#board55").length > 0){
    b55.board_f =b55.begin_position(); 
    b55.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3   d7-d6,    3. d2-d4  Ñc8-g4,'+
                     '4. d4-e5   Ñg4-f3,   5. Ôd1-f3   d6-e5,    6. Ñf1-c4 Êg8-f6,'+
                     '7. Ôf3-b3  Ôd8-e7,   8. Êb1-c3   c7-c6,    9. Ñc1-g5 b7-b5,'+
                    '10. Êc3-b5  c6-b5,   11. Ñc4-b5   Êb8-d7,  12. 0-0-0  Ëa8-d8,'+
                    '13. Ëd1-d7  Ëd8-d7,  14. Ëh1-d1   Ôe7-e6,  15. Ñb5-d7 Êf6-d7,'+
                    '16. Ôb3-b8  Êd7-b8,  17. Ëd1-d8.';
    b55.make_moves(b55.parser_record_game());  
    b55.link_events('#b55-begin','#b55-prev','#b55-next','#b55-last');
  }  

//Ñåíàíñ â Ñåíò-Äæåìñ êëóáå, Ëîíäîí, 26 àïðåëÿ 1859 ãîä
//Äåáþò äâóõ êîíåé, Äå Ðèâüåð - Ìîðôè
  if($("#board014").length > 0){
    b014.board_f =b014.begin_position(); 
    b014.record_game ='1.  e2-e4   e7-e5,   2. Êg1-f3  Êb8-c6,  3. Ñf1-c4  Êg8-f6,'+
                      '4.  Êf3-g5  d7-d5,   5. e4-d5   Êc6-a5,  6. d2-d3   h7-h6,'+
                      '7.  Êg5-f3  e5-e4,   8. Ôd1-e2  Êa5-c4,  9. d3-c4   Ñf8-c5,'+
                      '10. h2-h3   0-0,    11. Êf3-h2  Êf6-h7, 12. Êb1-c3  f7-f5,'+
                      '13. Ñc1-e3  Ñc5-b4, 14. Ôe2-d2  Ñc8-d7, 15. g2-g3   Ôd8-e7,'+
                      '16. a2-a3   Ñb4-d6, 17. Êc3-e2  b7-b5,  18. c4-b5   Ñd7-b5,'+
                      '19. Êe2-d4  Ñb5-c4, 20. Êd4-e6  Ëf8-e8, 21. Ôd2-d4  Ñc4-a6,'+
                      '22. c2-c4   c7-c5,  23. Ôd4-c3  Ña6-c8, 24. Êe6-f4  Ëa8-b8,'+
                      '25. Ëa1-b1  g7-g5,  26. Êf4-e2  Êh7-f8, 27. h3-h4   Êf8-g6,'+
                      '28. h4-g5   h6-g5,  29. Ôc3-c1  Êg6-e5, 30. Ñe3-g5  Êe5-d3,'+
                      '31. Êðe1-f1 Ôe7-g7, 32. Ôc1-d2  Êd3-b2, 33. Ôd2-c2  Ñc8-a6,'+
                      '34. Ñg5-c1  Êb2-c4, 35. Ôc2-a4  Êc4-d2, 36. Êðf1-g2 Êd2-b1,'+
                      '37. Ôa4-a6  Ëb8-b6, 38. Ôa6-a4  Ëe8-b8, 39. Êh2-f1  Ñd6-e5,'+
                      '40. Êf1-e3  f5-f4,  41. Êe2-f4  Ñe5-f4, 42. Êe3-f5  Ôg7-f7,'+
                      '43. Ñc1-f4  Ôf7-f5, 44. Ñf4-b8  Ëb6-b8, 45. Ôa4-a7  Ëb8-f8,'+
                      '46. Ôa7-c5  Ôf5-f3, 47. Êðg2-g1 Êb1-c3, 48. Ëh1-h4  Êc3-e2,'+
                      '49. Êðg1-h2 Ôf3-f2, 50. Ôc5-f2  Ëf8-f2, 51. Êðh2-h3 Êe2-g1,'+
                      '52. Êðh3-g4 e4-e3,  53. Êðg4-h5 e3-e2,  54. Ëh4-e4  Ëf2-f1.';
    b014.make_moves(b014.parser_record_game());  
    b014.link_events('#b014-begin','#b014-prev','#b014-next','#b014-last');
  }  

//Ñåíàíñ â Ñåíò-Äæåìñ êëóáå, Ëîíäîí, 26 àïðåëÿ 1859 ãîä
//Èñïàíñêàÿ ïàðòèÿ, Ëåâåíòàëü - Ìîðôè
  if($("#board016").length > 0){
    b016.board_f =b016.begin_position(); 
    b016.record_game ='1.  e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Ñf1-b5  a7-a6,'+
                      '4.  Ñb5-a4  Êg8-f6,   5. 0-0     Ñf8-e7,   6. d2-d4   e5-d4,'+
                      '7.  e4-e5   Êf6-e4,   8. Ña4-c6  d7-c6,    9. Ôd1-d4  Ñc8-f5,'+
                      '10. Êb1-c3  Ñe7-c5,  11. Ôd4-d8  Ëa8-d8,  12. Êf3-h4  Êe4-c3,'+
                      '13. Êh4-f5  Êc3-e2,  14. Êðg1-h1 g7-g6,   15. Êf5-g3  Êe2-g3,'+
                      '16. h2-g3   h7-h6,   17. Ëa1-b1  Êðe8-e7, 18. b2-b4   Ñc5-d4,'+
                      '19. f2-f4   Êðe7-e6, 20. Ëb1-b3  h6-h5,   21. Ëb3-d3  Ñd4-b6,'+
                      '22. Ëf1-d1  Ëd8-d3,  23. Ëd1-d3  Êðe6-f5, 24. Ñc1-b2  Ëh8-h7,'+
                      '25. Ñb2-d4  h5-h4,   26. Ñd4-b6  h4-g3,   27. Êðh1-g1 c7-b6,'+
                      '28. Ëd3-d7  Êðf5-e6, 29. Ëd7-b7  Ëh7-h4,  30. Ëb7-b6  Ëh4-f4,'+
                      '31. Ëb6-c6  Êðe6-e5, 32. Ëc6-c5  Êðe5-d6, 33. Ëc5-g5  Ëf4-b4,'+
                      '34. Ëg5-g3  Ëb4-a4,  35. a2-a3   Ëa4-c4,  36. Ëg3-d3  Êðd6-e6,'+
                      '37. Ëd3-b3  Ëc4-c2,  38. Ëb3-b6  Êðe6-f5, 39. Ëb6-a6  g6-g5,'+
                      '40. Ëa6-b6  Ëc2-a2,  41. Ëb6-b3  g5-g4,   42. Ëb3-b5  Êðf5-f4,'+
                      '43. Ëb5-b3  f7-f5,   44. g2-g3   Êðf4-e4, 45. Êðg1-f1 Êðe4-e5,'+
                      '46. Êðf1-g1 f5-f4,   47. Ëb3-b4.';
    b016.make_moves(b016.parser_record_game());  
    b016.link_events('#b016-begin','#b016-prev','#b016-next','#b016-last');
  }
  
//Çàùèòà Ôèëèäîðà, Ìîðôè - Äæåëüåí, Íüþ-Éîðê, ìàé 1859 (áåëûå áåç êîíÿ b1)
  if($("#board57").length > 0){
    b57.board_f =b57.begin_position(); 
    b57.board_f['B'][1] ='';
    b57.record_game ='1. e2-e4  e7-e5,   2. Êg1-f3  d7-d6,   3. d2-d4  e5-d4,'+
                     '4. Ñf1-c4 Êb8-c6,  5. c2-c3   Êc6-e5,  6. Êf3-e5 d6-e5,'+
                     '7. Ôd1-b3 Ôd8-e7,  8. f2-f4   d4-c3,   9. 0-0    c7-c6,'+
                    '10. f4-e5  Ñc8-e6, 11. Ñc4-e6  f7-e6,  12. Ñc1-g5 Ôe7-g5,'+
                    '13. Ôb3-b7 Ñf8-c5, 14. Êðg1-h1 Ôg5-e5, 15. Ôb7-a8 Êðe8-d7,'+
                    '16. Ôa8-b7 Ôe5-c7, 17. Ëa1-d1  Ñc5-d6, 18. Ëd1-d6 Êðd7-d6,' +
                    '19. Ëf1-d1.';
    b57.make_moves(b57.parser_record_game());  
    b57.link_events('#b57-begin','#b57-prev','#b57-next','#b57-last');
  }  
  
//Øîòëàíäñêèé ãàìáèò, Ìîðôè - Ïåððýí, Íüþ-Éîðê, 11 ìàé 1859 (áåëûå áåç êîíÿ b1)
  if($("#board58").length > 0){
    b58.board_f =b58.begin_position(); 
    b58.board_f['B'][1] ='';
    b58.record_game ='1. e2-e4   e7-e5,   2. Êg1-f3  Êb8-c6,   3. d2-d4  e5-d4,'+
                     '4. Ñf1-c4  Ñf8-c5,  5. 0-0     d7-d6,    6. b2-b4  Ñc5-b6,'+
                     '7. b4-b5   Êc6-e5,  8. Êf3-e5  d6-e5,    9. f2-f4  d4-d3,'+
                    '10. Êðg1-h1 Ôd8-d4, 11. Ñc4-f7  Êðe8-f7, 12. f4-e5  Êðf7-e8,'+
                    '13. Ôd1-f3  Êg8-e7, 14. Ôf3-f7  Êðe8-d8, 15. Ñc1-g5 Ôd4-e5,'+
                    '16. Ëa1-d1  Ôe5-g5, 17. Ëd1-d3  Ñc8-d7,  18. Ôf7-f8 Ëh8-f8,' +
                    '19. Ëf1-f8.';
    b58.make_moves(b58.parser_record_game());  
    b58.link_events('#b58-begin','#b58-prev','#b58-next','#b58-last');
  }  

//Èòàëüÿíñêàÿ ïàðòèÿ, Ìîðôè - äå Ðèâüåð, Ïàðèæ, ÿíâàðü 1863
  if($("#board63").length > 0){
    b63.board_f =b63.begin_position(); 
    b63.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Ñf1-c4  Ñf8-c5,'+
                     '4. c2-c3   Ôd8-e7,   5. d2-d4   Ñc5-b6,   6. 0-0     d7-d6,'+
                     '7. h2-h3   Êg8-f6,   8. Ëf1-e1  h7-h6,    9. a2-a4   a7-a5,'+
                    '10. Êb1-a3  Êc6-d8,  11. Êa3-c2  Ñc8-e6,  12. Êc2-e3  Ñe6-c4,'+
                    '13. Êe3-c4  Êf6-d7,  14. Êc4-e3  g7-g6,   15. Êe3-d5  Ôe7-e6,'+
                    '16. Ñc1-h6  f7-f6,   17. Ñh6-g7  Ëh8-h5,  18. g2-g4   Ëh5-h3,'+
                    '19. Êd5-f6  Êd7-f6,  20. Êf3-g5  Ôe6-d7,  21. Ñg7-f6  Ëh3-h4,'+
                    '22. f2-f3   e5-d4,   23. c3-d4   Ëh4-h6,  24. Êðg1-g2 Êd8-f7,'+
                    '25. Ëe1-h1  Êf7-g5,  26. Ëh1-h6  Êg5-h7,  27. Ôd1-h1  Êh7-f6,'+
                    '28. Ëh6-h8  Êðe8-e7, 29. Ëh8-a8  Ñb6-d4,  30. Ôh1-h6  Ôd7-c6,'+
                    '31. Ëa1-c1  Ôc6-b6,  32. Ëc1-c7  Êðe7-e6, 33. Ëa8-e8  Êf6-e8,'+
                    '34. Ôh6-g6  Êðe6-e5, 35. Ôg6-f5.';
    b63.make_moves(b63.parser_record_game());  
    b63.link_events('#b63-begin','#b63-prev','#b63-next','#b63-last');
  }  

//Äåáþò äâóõ êîíåé, äå Ðèâüåð - Ìîðôè, Ïàðèæ, ÿíâàðü 1863
  if($("#board64").length > 0){
    b64.board_f =b64.begin_position(); 
    b64.record_game ='1. e2-e4   e7-e5,    2. Êg1-f3  Êb8-c6,   3. Ñf1-c4  Êg8-f6,'+
                     '4. Êf3-g5  d7-d5,    5. e4-d5   Êc6-a5,   6. d2-d3   h7-h6,'+
                     '7. Êg5-f3  e5-e4,    8. Ôd1-e2  Êa5-c4,   9. d3-c4   Ñf8-c5,'+
                    '10. h2-h3   0-0,     11. Êf3-h2  Êf6-h7,  12. Êb1-d2  f7-f5,'+
                    '13. Êd2-b3  Ñc5-d6,  14. 0-0     Ñd6-h2,  15. Êðg1-h2 f5-f4,'+
                    '16. Ôe2-e4  Êh7-g5,  17. Ôe4-d4  Êg5-f3,  18. g2-f3   Ôd8-h4,'+
                    '19. Ëf1-h1  Ñc8-h3,  20. Ñc1-d2  Ëf8-f6.';
    b64.make_moves(b64.parser_record_game());  
    b64.link_events('#b64-begin','#b64-prev','#b64-next','#b64-last');
  }  

//Ãàìáèò êîíÿ, Ìîðôè - Ìîðèàí, 1869 (áåëûå áåç êîíÿ b1)
  if($("#board68").length > 0){
    b68.board_f =b68.begin_position(); 
    b68.board_f['B'][1] ='';
    b68.record_game ='1. e2-e4   e7-e5,   2. f2-f4   e5-f4,    3. Êg1-f3 g7-g5,'+
                     '4. Ñf1-c4  g5-g4,   5. d2-d4   g4-f3,    6. Ôd1-f3 d7-d6,'+
                     '7. 0-0     Ñc8-e6,  8. d4-d5   Ñe6-c8,   9. Ñc1-f4 Ôd8-d7,'+
                    '10. e4-e5   Ôd7-g4, 11. Ôf3-e3  Ñf8-e7,  12. e5-d6  c7-d6,'+
                    '13. Ëa1-e1  h7-h5,  14. Ñf4-d6  Ôg4-d7,  15. Ñd6-e7 Êg8-e7,'+
                    '16. Ñc4-b5.';
    b68.make_moves(b68.parser_record_game());  
    b68.link_events('#b68-begin','#b68-prev','#b68-next','#b68-last');
  }  
  
//Äåáþò Áåðäà, Ìîðôè - Ìîðèàí, 1869 (áåëûå áåç êîíÿ b1)
  if($("#board69").length > 0){
    b69.board_f =b69.begin_position(); 
    b69.board_f['B'][1] ='';
    b69.record_game ='1. f2-f4   e7-e6,   2. Êg1-f3  f7-f5,    3. e2-e3   Êg8-f6,'+
                     '4. Ñf1-e2  Ñf8-e7,  5. 0-0     b7-b6,    6. b2-b3   0-0,'+
                     '7. Ñc1-b2  Ñc8-b7,  8. h2-h3   h7-h6,    9. Êðg1-h2 c7-c5,'+
                    '10. Ëf1-g1  Êb8-c6, 11. g2-g4   f5-g4,   12. h3-g4   Ôd8-c7,'+
                    '13. g4-g5   h6-g5,  14. Êf3-g5  Êc6-d4,  15. Ñe2-d3  Êd4-f5,'+
                    '16. Ôd1-f1  g7-g6,  17. Ôf1-h3  Ëf8-f7,  18. Êg5-f7  Êðg8-f7,'+
                    '19. Ñd3-f5  e6-f5,  20. Ñb2-f6  Ñe7-f6,  21. Ôh3-h7  Êðf7-e6,'+
                    '22. Ëg1-g6  Ôc7-d8, 23. Ëa1-g1  Êðe6-d6, 24. Ëg6-f6  Êðd6-c7,'+
                    '25. Ëf6-f7  Ñb7-c6, 26. Ëg1-g7  Ôd8-e8,  27. Ôh7-f5  Ôe8-h8,'+
                    '28. Ëg7-h7.';
    b69.make_moves(b69.parser_record_game());  
    b69.link_events('#b69-begin','#b69-prev','#b69-next','#b69-last');
  }  
  

})


