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
//������ ������, ����� - �.����� (����) ����� ������, ����� 1849
  if($("#board1").length > 0){
    b1.board_f =b1.begin_position();
    b1.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �f1-c4  �f8-c5,'+
                    '4. b2-b4   �c5-b4,   5. c2-c3   �b4-c5,   6. d2-d4   e5-d4,'+
                    '7. c3-d4   �c5-b6,   8. 0-0     �c6-a5,   9. �c4-d3  �g8-e7,'+
                   '10. �b1-c3  0-0,     11. �c1-a3  d7-d6,   12. e4-e5   �c8-f5,'+
                   '13. e5-d6   c7-d6,   14. �c3-e4  d6-d5,   15. �e4-f6  g7-f6,'+
                   '16. �a3-e7  �d8-e7,  17. �d3-f5  �a5-c4,  18. �f1-e1  �e7-d6,'+
                   '19. �f3-e5  f6-e5,   20. �d1-g4  ��g8-h8, 21. �g4-h5  ��h8-g7,'+
                   '22. �h5-g5  ��g7-h8, 23. �g5-h5  h7-h6,   24. �e1-e5  �c4-e5,'+
                   '25. d4-e5   �d6-c6,  26. e5-e6   ��h8-g7, 27. g2-g4   �c6-c3,'+
                   '28. g4-g5   �c3-a1,  29. ��g1-g2 �a1-f6,  30. g5-f6   ��g7-f6,'+
                   '31. e6-f7   �f8-f7,  32. �h5-g6  ��f6-e7, 33. �g6-e6  ��e7-f8,'+
                   '34. �e6-h6  �f7-g7,  35. �f5-g6  ��f8-g8, 36. h2-h4   d5-d4,'+
                   '37. h4-h5   d4-d3,   38. �h6-g5  �a8-d8,  39. h5-h6   d3-d2,'+
                   '40. �g5-f6  �g7-d7,  41. �g6-f5  d2-d1�,  42. h6-h7   �d7-h7,'+
                   '43. �f5-e6  �h7-f7,  44. �e6-f7  ��g8-h7, 45. �f6-g6  ��h7-h8,'+
                   '46. �g6-h6.';
    b1.make_moves(b1.parser_record_game());  
    b1.link_events('#b1-begin','#b1-prev','#b1-next','#b1-last');
    b1.link_events('#b1-2-begin','#b1-2-prev','#b1-2-next','#b1-2-last');
  }   
    
//����������� ������, ����� - �.����� ����� ������, 22 ���� 1849
  if($("#board2").length > 0){
    b2.board_f =b2.begin_position();
    b2.record_game ='1. e2-e4  e7-e5,'+
                    '2. �g1-f3 �b8-c6,'+
                    '3. �f1-c4 �f8-c5,'+
                    '4. c2-c3  d7-d6,'+
                    '5. 0-0    �g8-f6,'+
                    '6. d2-d4  e5-d4,'+
                    '7. c3-d4  �c5-b6,'+
                    '8. h2-h3  h7-h6,'+
                    '9. �b1-c3 0-0,'+
                   '10. �c1-e3 �f8-e8,'+
                   '11. d4-d5  �b6-e3,'+
                   '12. d5-c6  �e3-b6,'+
                   '13. e4-e5  d6-e5,'+
                   '14. �d1-b3 �e8-e7,'+
                   '15. �c4-f7 �e7-f7,'+
                   '16. �f3-e5 �d8-e8,'+
                   '17. c6-b7  �c8-b7,'+
                   '18. �a1-e1 �b7-a6,'+
                   '19. �e5-g6 �e8-d8,'+
                   '20. �e1-e7.';
    b2.make_moves(b2.parser_record_game());  
    b2.link_events('#b2-begin','#b2-prev','#b2-next','#b2-last');
  }   

//������� ������, ����� - ��������� ����� ������, 22 ��� 1850
  if($("#board3").length > 0){
    b3.board_f =b3.begin_position();
    b3.record_game ='1. e2-e4  e7-e5,'+
                    '2. �g1-f3 �g8-f6,'+
                    '3. �f3-e5 d7-d6,'+
                    '4. �e5-f3 �f6-e4,'+
                    '5. �d1-e2 �d8-e7,'+
                    '6. d2-d3  �e4-f6,'+
                    '7. �b1-c3 �c8-e6,'+
                    '8. �c1-g5 h7-h6,'+
                    '9. �g5-f6 �e7-f6,'+
                   '10. d3-d4  c7-c6,'+
                   '11. 0-0-0  d6-d5,'+
                   '12. �f3-e5 �f8-b4,'+
                   '13. �c3-d5 �e6-d5,'+
                   '14. �e5-g6 �f6-e6,'+
                   '15. �g6-h8 �e6-e2,'+
                   '16. �f1-e2 ��e8-f8,'+
                   '17. a2-a3  �b4-d6,'+
                   '18. �e2-d3 ��f8-g8,'+
                   '19. �h8-f7 ��g8-f7,'+
                   '20. f2-f3  b7-b5,'+
                   '21. �d3-e4 �b8-d7,'+
                   '22. �d1-e1 �d7-f6,'+
                   '23. �e1-e2 �a8-e8,'+
                   '24. �e4-d5 c6-d5,'+
                   '25. �e2-e8 �f6-e8,'+
                   '26. g2-g3  g7-g5,'+
                   '27. ��c1-d2 �e8-g7,'+
                   '28. �h1-a1 a7-a5,'+
                   '29. ��d2-d3 ��f7-e6,'+
                   '30. a3-a4  b5-b4,'+
                   '31. c2-c4  �d6-c7,'+
                   '32. �a1-e1 ��e6-d6,'+
                   '33. �e1-e5 d5-c4,'+
                   '34. ��d3-c4 �g7-e6,'+
                   '35. �e5-b5 �e6-f8,'+
                   '36. �b5-d5 ��d6-e6,'+
                   '37. �d5-c5 ��e6-d6,'+
                   '38. d4-d5  ��d6-d7,'+
                   '39. �c5-c6 �c7-d6,'+
                   '40. �c6-a6 �f8-g6,'+
                   '41. �a6-a5 �g6-e5,'+
                   '42. ��c4-b5 b4-b3,'+
                   '43. �a5-a7 ��d7-d8,'+
                   '44. f3-f4  g5-f4,'+
                   '45. g3-f4 �e5-d3,'+
                   '46. ��b5-c4 �d3-f4,'+
                   '47. �a7-h7 �d6-e5,'+
                   '48. �h7-h6 �e5-b2,'+
                   '49. ��c4-b3 �b2-g7,'+
                   '50. �h6-h7 �g7-e5,'+
                   '51. a4-a5  �f4-d5,'+
                   '52. �h7-h5 �e5-h2,'+
                   '53. �h5-d5 ��d8-c8,'+
                   '54. �d5-b5 ��c8-c7,'+
                   '55. ��b3-c4 ��c7-c6,'+
                   '56. �b5-h5 �h2-g1,'+
                   '57. �h5-h6 ��c6-c7,'+
                   '58. ��c4-b5 ��c7-b8.';
    b3.make_moves(b3.parser_record_game());  
    b3.link_events('#b3-begin','#b3-prev','#b3-next','#b3-last');
    b3.link_events('#b3-2-begin','#b3-2-prev','#b3-2-next','#b3-2-last');
  }  
  
//������������ ������, ����� - ��������� ����� ������, 22 ��� 1850
  if($("#board4").length > 0){
    b4.board_f =b4.begin_position();
    b4.record_game ='1. e2-e4  c7-c5,'+
                    '2. f2-f4  e7-e6,'+
                    '3. �g1-f3 d7-d5,'+
                    '4. e4-d5  e6-d5,'+
                    '5. d2-d4  �c8-g4,'+
                    '6. �f1-e2 �g4-f3,'+
                    '7. �e2-f3 �g8-f6,'+
                    '8. 0-0    �f8-e7,'+
                    '9. �c1-e3 c5-d4,'+
                   '10. �e3-d4 0-0,'+
                   '11. �b1-c3 �b8-c6,'+
                   '12. �d4-f6 �e7-f6,'+
                   '13. �c3-d5 �f6-b2,'+
                   '14. �a1-b1 �b2-d4,'+
                   '15. ��g1-h1 �a8-b8,'+
                   '16. c2-c3  �d4-c5,'+
                   '17. f4-f5  �d8-h4,'+
                   '18. g2-g3  �h4-g5,'+
                   '19. f5-f6  �c6-e5,'+
                   '20. f6-g7  �f8-d8,'+
                   '21. �f3-e4 �g5-g7,'+
                   '22. �d1-h5 �d8-d6,'+
                   '23. �e4-h7 ��g8-f8,'+
                   '24. �h7-e4 �d6-h6,'+
                   '25. �h5-f5 �g7-g3,'+
                   '26. �b1-b2 �b8-e8,'+
                   '27. �d5-f6 �e8-e6,'+
                   '28. �b2-g2 �g3-g2,'+
                   '29. �e4-g2 �h6-f6,'+
                   '30. �f5-f6 �e6-f6,'+
                   '31. �f1-f6 �e5-g4,'+
                   '32. �f6-f5 b7-b6,'+
                   '33. �g2-d5 �g4-h6,'+
                   '34. �f5-f6 ��f8-g7,'+
                   '35. �f6-c6 a7-a5,'+
                   '36. �c6-c7 ��g7-g6,'+
                   '37. ��h1-g2 f7-f6,'+
                   '38. ��g2-f3 �h6-f5,'+
                   '39. �d5-e4 ��g6-g5,'+
                   '40. �e4-f5 ��g5-f5,'+
                   '41. h2-h4  ��f5-g6,'+
                   '42. �c7-c6 ��g6-h5,'+
                   '43. ��f3-g3 f6-f5,'+
                   '44. �c6-f6 f5-f4,'+
                   '45. ��g3-f4.';
    b4.make_moves(b4.parser_record_game());  
    b4.link_events('#b4-begin','#b4-prev','#b4-next','#b4-last');
  }  

//������ ����, ����� - ���� ����� ������, 1856. ����� ��� ����� �� a1 � ���� �� b1
  if($("#board7").length > 0){
    b7.board_f =b7.begin_position(); 
    b7.board_f['A'][1] ='';
    b7.board_f['B'][1] ='';
    b7.record_game ='1. e2-e4   e7-e5,   2. f2-f4  e5-f4,   3. �g1-f3 g7-g5,'+
                    '4. �f1-c4  �d8-e7,  5. d2-d4  d7-d5,   6. �c4-d5 c7-c6,'+
                    '7. �d5-f7  �e7-f7,  8. �f3-e5 �f7-f6,  9. �d1-h5 ��e8-e7,'+
                   '10. h2-h4   g5-h4,  11. 0-0    �f8-h6, 12. b2-b3  �b8-d7,'+
                   '13. �c1-a3  c6-c5,  14. �f1-d1 �d7-e5, 15. �a3-c5 ��e7-e6,'+
                   '16. �h5-e8  �g8-e7, 17. d4-d5.';
    b7.make_moves(b7.parser_record_game());  
    b7.link_events('#b7-begin','#b7-prev','#b7-next','#b7-last');
  }  

//������ �����, ����� - ������, 1855. ����� ��� ����� �� a1
  if($("#board0").length > 0){
    b0.board_f =b0.begin_position(); 
    b0.board_f['A'][1] ='';
    b0.record_game ='1. e2-e4   e7-e5,   2. f2-f4  e5-f4,   3. �f1-c4  �d8-h4,'+
                    '4. ��e1-f1 b7-b5,   5. �c4-d5 �b8-c6,  6. �g1-f3  �h4-h5,'+
                    '7. d2-d4   �g8-f6,  8. �d5-b3 �c8-a6,  9. �d1-e2  �c6-d4,'+
                   '10. �f3-d4  b5-b4,  11. �e2-a6 �h5-d1, 12. ��f1-f2 �f6-g4.';
    b0.make_moves(b0.parser_record_game());  
    b0.link_events('#b0-begin','#b0-prev','#b0-next','#b0-last');
  }  

//����������� ������, ������� ����� 8 ������� 1857 ���� ���-����
  if($("#board8").length > 0){
    b8.board_f =b8.begin_position(); 
    b8.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �f1-c4  �f8-c5,'+
                    '4. c2-c3   �g8-f6,   5. d2-d3   d7-d6,    6. h2-h3   �c8-e6,'+
                    '7. �c4-b3  d6-d5,    8. e4-d5   �e6-d5,   9. 0-0     0-0,'+
                   '10. �c1-g5  �d5-b3,  11. a2-b3   h7-h6,   12. �g5-h4  g7-g5,'+
                   '13. �h4-g3  e5-e4,   14. �f3-e5  �c6-e5,  15. �g3-e5  e4-d3,'+
                   '16. �e5-f6  �d8-f6,  17. �d1-d3  �a8-d8,  18. �d3-c2  �f8-e8,'+
                   '19. b3-b4   �c5-b6,  20. �b1-a3  �f6-f4,  21. �a1-d1  c7-c6,'+
                   '22. �d1-d3  �b6-f2,  23. ��g1-h1 �d8-d3,  24. �c2-d3  �e8-e3,'+
                   '25. �d3-d8  ��g8-g7, 26. �d8-d4  �f4-d4,  27. c3-d4   �e3-e2,'+
                   '28. �a3-c4  �e2-e1,  29. �f1-e1  �f2-e1,  30. �c4-a5  �e1-b4,'+
                   '31. �a5-b7  ��g7-f6, 32. �b7-d8  c6-c5,   33. �d8-c6  ��f6-e6,'+
                   '34. d4-c5   �b4-c5,  35. g2-g4   ��e6-d5, 36. �c6-d8  f7-f6,'+
                   '37. ��h1-g2 a7-a5,   38. ��g2-f3 a5-a4,   39. ��f3-e2 �c5-d4,'+
                   '40. ��e2-d3 �d4-b2,  41. �d8-f7  �b2-e5,  42. ��d3-c2 ��d5-c4,'+
                   '43. �f7-d8  a4-a3,   44. �d8-b7  a3-a2,   45. �b7-a5  ��c4-b4,'+
                   '46. �a5-b3  ��b4-a3.';
    b8.make_moves(b8.parser_record_game());  
    b8.link_events('#b8-begin','#b8-prev','#b8-next','#b8-last');
  }  
  
//����������� ������ ���������� ����� 22 ������� 1857 ���� ���-����
  if($("#board10").length > 0){
    b10.board_f =b10.begin_position(); 
    b10.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. d2-d4  e5-d4,'+
                     '4. �f1-c4  �g8-f6,   5. e4-e5   d7-d5,    6. �c4-b5 �f6-e4,'+
                     '7. �f3-d4  �c8-d7,   8. �d4-c6  b7-c6,    9. �b5-d3 �f8-c5,'+
                    '10. �d3-e4  �d8-h4,  11. �d1-e2  d5-e4,   12. �c1-e3 �d7-g4,'+
                    '13. �e2-c4  �c5-e3,  14. g2-g3   �h4-d8,  15. f2-e3  �d8-d1,'+
                    '16. ��e1-f2 �d1-f3,  17. ��f2-g1 �g4-h3,  18. �c4-c6 ��e8-f8,'+
                    '19. �c6-a8  ��f8-e7.';
    b10.make_moves(b10.parser_record_game());  
    b10.link_events('#b10-begin','#b10-prev','#b10-next','#b10-last');
  }  
  
//������� ������ ����� ���������� 23 ������� 1857 ���� ���-����
  if($("#board11").length > 0){
    b11.board_f =b11.begin_position(); 
    b11.record_game ='1. e2-e4   e7-e5,    2. �f1-c4  �g8-f6,   3. �g1-f3  �f6-e4,'+
                     '4. �b1-c3  d7-d5,    5. �c4-d5  �e4-f6,   6. �d5-b3  �f8-d6,'+
                     '7. d2-d3   0-0,      8. h2-h3   h7-h6,    9. �c1-e3  �b8-c6,'+
                    '10. �d1-d2  �c6-a5,  11. g2-g4   �a5-b3,  12. a2-b3   �c8-d7,'+
                    '13. �h1-g1  �f6-h7,  14. �c3-e4  ��g8-h8, 15. g4-g5   h6-h5,'+
                    '16. �f3-h4  g7-g6,   17. �d2-e2  �d7-c6,  18. f2-f4   e5-f4,'+
                    '19. �e3-d4  ��h8-g8, 20. �h4-f5  �f8-e8,  21. �f5-h6  ��g8-f8,'+
                    '22. 0-0-0   �c6-e4,  23. d3-e4   �d8-e7,  24. e4-e5   �d6-e5,'+
                    '25. �d4-e5  �e7-e5,  26. �d1-d7  �e5-g7,  27. �e2-c4  �e8-e7,'+
                    '28. �d7-e7  ��f8-e7, 29. �g1-e1.';
    b11.make_moves(b11.parser_record_game());  
    b11.link_events('#b11-begin','#b11-prev','#b11-next','#b11-last');
  }  

//����� ������� ����� �������� ����� 8 ������ 1857 ���� ���-����
  if($("#board12").length > 0){
    b12.board_f =b12.begin_position(); 
    b12.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �b1-c3  �g8-f6,'+
                     '4. �f1-b5  �f8-c5,   5. 0-0     0-0,      6. �f3-e5  �f8-e8,'+
                     '7. �e5-c6  d7-c6,    8. �b5-c4  b7-b5,    9. �c4-e2  �f6-e4,'+
                    '10. �c3-e4  �e8-e4,  11. �e2-f3  �e4-e6,  12. c2-c3   �d8-d3,'+
                    '13. b2-b4   �c5-b6,  14. a2-a4   b5-a4,   15. �d1-a4  �c8-d7,'+
                    '16. �a1-a2  �a8-e8,  17. �a4-a6  �d3-f3,  18. g2-f3   �e6-g6,'+
                    '19. ��g1-h1 �d7-h3,  20. �f1-d1  �h3-g2,  21. ��h1-g1 �g2-f3,'+
                    '22. ��g1-f1 �f3-g2,  23. ��f1-g1 �g2-h3,  24. ��g1-h1 �b6-f2,'+
                    '25. �a6-f1  �h3-f1,  26. �d1-f1  �e8-e2,  27. �a2-a1  �g6-h6,'+
                    '28. d2-d4   �f2-e3.';
    b12.make_moves(b12.parser_record_game());  
    b12.link_events('#b12-begin','#b12-prev','#b12-next','#b12-last');
  }  

//������ ������ ����� - ������, ���-����, ����� 1857 ����
  if($("#board13").length > 0){
    b13.board_f =b13.begin_position(); 
    b13.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �f1-c4  �f8-c5,'+
                     '4. b2-b4   �c5-b4,   5. c2-c3   �b4-a5,   6. d2-d4   e5-d4,'+
                     '7. 0-0     d7-d6,    8. c3-d4   �a5-b6,   9. �b1-c3  �g8-f6,'+
                    '10. e4-e5   d6-e5,   11. �c1-a3  �b6-d4,  12. �d1-b3  �c8-e6,'+
                    '13. �c4-e6  f7-e6,   14. �b3-e6  �c6-e7,  15. �f3-d4  e5-d4,'+
                    '16. �f1-e1  �f6-g8,  17. �c3-d5  �d8-d7,  18. �a3-e7  �d7-e6,'+
                    '19. �e1-e6  ��e8-d7, 20. �a1-e1  �a8-e8,  21. �e6-e4  c7-c6,'+
                    '22. �e4-d4  c6-d5,   23. �d4-d5  ��d7-c6, 24. �d5-d6  ��c6-c7,'+
                    '25. �e1-c1  ��c7-b8, 26. �e7-h4  �g8-h6,  27. �h4-g3  ��b8-a8,'+
                    '28. h2-h3   �h6-f5,  29. �d6-d7  g7-g6,   30. �c1-c7  �f5-g3,'+
                    '31. f2-g3   �e8-b8,  32. �d7-h7  �h8-h7,  33. �c7-h7  a7-a5,'+
                    '34. h3-h4   �b8-g8,  35. g3-g4   b7-b5,   36. h4-h5   a5-a4,'+
                    '37. h5-h6   b5-b4,   38. �h7-g7  �g8-h8,  39. h6-h7   b4-b3,'+
                    '40. �g7-g8  ��a8-b7, 41. �g8-h8  b3-b2,   42. �h8-b8  ��b7-b8,'+
                    '43. h7-h8�.';
    b13.make_moves(b13.parser_record_game());  
    b13.link_events('#b13-begin','#b13-prev','#b13-next','#b13-last');
  }  

//������ �������� ��������+���� - �����+�����, ������, ���� 1858 ����
  if($("#board19").length > 0){
    b19.board_f =b19.begin_position(); 
    b19.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  d7-d6,    3. d2-d4   f7-f5,'+
                     '4. d4-e5   f5-e4,    5. �f3-g5  d6-d5,    6. e5-e6   �g8-h6,'+
                     '7. �b1-c3  c7-c6,    8. �g5-e4  d5-e4,    9. �d1-h5  g7-g6,'+
                    '10. �h5-e5  �h8-g8,  11. �c1-h6  �f8-h6,  12. �a1-d1  �d8-g5,'+
                    '13. �e5-c7  �c8-e6,  14. �c7-b7  e4-e3,   15. f2-f3   �g5-e7,'+
                    '16. �b7-a8  ��e8-f7, 17. �c3-e4  �h6-f4,  18. �f1-e2  ��f7-g7,'+
                    '19. 0-0     �e7-c7,  20. �e4-c5  �f4-h2,  21. ��g1-h1 �e6-c8,'+
                    '22. �d1-d4  �h2-g3,  23. �d4-e4  ��g7-h8, 24. �f1-d1  �c7-g7,'+
                    '25. �e4-h4  �g3-h4,  26. �a8-b8  �c8-a6,  27. �b8-h2  �a6-e2,'+
                    '28. �d1-d7  �g7-h6,  29. �c5-e4  �e2-c4,  30. �e4-f6  e3-e2,'+
                    '31. �d7-e7  �h6-c1,  32. �h2-g1  �c1-g1,  33. ��h1-g1 e2-e1�,'+
                    '34. �e7-e1  �h4-e1.';
    b19.make_moves(b19.parser_record_game());  
    b19.link_events('#b19-begin','#b19-prev','#b19-next','#b19-last');
  }  

//������ �������� ����� - �����, ������, ���� 1858 ����
  if($("#board21").length > 0){
    b21.board_f =b21.begin_position(); 
    b21.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  d7-d6,    3. d2-d4   f7-f5,'+
                     '4. d4-e5   f5-e4,    5. �f3-g5  d6-d5,    6. e5-e6   �f8-c5,'+
                     '7. �g5-f7  �d8-f6,   8. �c1-e3  d5-d4,    9. �e3-g5  �f6-f5,'+
                    '10. �f7-h8  �f5-g5,  11. �f1-c4  �b8-c6,  12. �h8-f7  �g5-g2,'+
                    '13. �h1-f1  �g8-f6,  14. f2-f3   �c6-b4,  15. �b1-a3  �c8-e6,'+
                    '16. �c4-e6  �b4-d3,  17. �d1-d3  e4-d3,   18. 0-0-0   �c5-a3,'+
                    '19. �e6-b3  d3-d2,   20. ��c1-b1 �a3-c5,  21. �f7-e5  ��e8-f8,'+
                    '22. �e5-d3  �a8-e8,  23. �d3-c5  �g2-f1,  24. �c5-e6  �e8-e6.';
    b21.make_moves(b21.parser_record_game());  
    b21.link_events('#b21-begin','#b21-prev','#b21-next','#b21-last');
  }  

//������ �������� ����� - �����, ������, ���� 1858 ����
  if($("#board22").length > 0){
    b22.board_f =b22.begin_position(); 
    b22.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  d7-d6,    3. d2-d4   e5-d4,'+
                     '4. �f3-d4  �g8-f6,   5. �f1-d3  �f8-e7,   6. �b1-c3  0-0,'+
                     '7. 0-0     c7-c5,    8. �d4-e2  �b8-c6,   9. f2-f4   a7-a6,'+
                    '10. a2-a4   �c8-g4,  11. h2-h3   �g4-e2,  12. �d1-e2  �f8-e8,'+
                    '13. �e2-f2  �a8-c8,  14. g2-g4   �c6-b4,  15. b2-b3   d6-d5,'+
                    '16. ��g1-h1 d5-e4,   17. �c3-e4  �f6-e4,  18. �d3-e4  �e7-f6,'+
                    '19. �e4-b7  �f6-a1,  20. �b7-c8  �d8-c8,  21. �c1-e3  �c8-c6,'+
                    '22. ��h1-h2 �a1-d4.';
    b22.make_moves(b22.parser_record_game());  
    b22.link_events('#b22-begin','#b22-prev','#b22-next','#b22-last');
  }  

//���������� ����������� ������ ����� - �����, ������, ���� 1858 ����
  if($("#board23").length > 0){
    b23.board_f =b23.begin_position(); 
    b23.record_game ='1. e2-e4   e7-e5,    2. f2-f4   �f8-c5,   3. �g1-f3  d7-d6,'+
                     '4. c2-c3   �c8-g4,   5. �f1-e2  �b8-c6,   6. b2-b4   �c5-b6,'+
                     '7. b4-b5   �c6-a5,   8. d2-d4   �g4-f3,   9. �e2-f3  e5-d4,'+
                    '10. c3-d4   �d8-f6,  11. �c1-e3  �a5-c4,  12. �e3-f2  �f6-f4,'+
                    '13. 0-0     �g8-f6,  14. �d1-d3  �c4-a5,  15. �b1-c3  0-0,'+
                    '16. g2-g3   �f4-h6,  17. ��g1-g2 �a8-e8,  18. �a1-e1  ��g8-h8,'+
                    '19. �f2-e3  �h6-g6,  20. �c3-e2  h7-h6,   21. �e3-d2  d6-d5,'+
                    '22. �e2-f4  �g6-h7,  23. e4-e5   �h7-d3,  24. �f4-d3  �a5-c4,'+
                    '25. �d2-b4  �f6-e4,  26. �b4-f8  �e8-f8,  27. �d3-f4  �e4-d2,'+
                    '28. �f3-d5  �d2-f1,  29. �d5-c4  �f1-d2,  30. �c4-d5  �b6-d4,'+
                    '31. e5-e6   g7-g5,   32. e6-e7   �f8-e8,  33. �d5-f7  g5-f4,'+                    
                    '34. g3-f4   �e8-e7,  35. �e1-e7.';
    b23.make_moves(b23.parser_record_game());  
    b23.link_events('#b23-begin','#b23-prev','#b23-next','#b23-last');
  }  

//������ �������� ���� - �����, ������, ���� 1858 ����
  if($("#board24").length > 0){
    b24.board_f =b24.begin_position(); 
    b24.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  d7-d6,    3. d2-d4   f7-f5,'+
                     '4. �b1-c3  f5-e4,    5. �c3-e4  d6-d5,    6. �e4-g3  e5-e4,'+
                     '7. �f3-e5  �g8-f6,   8. �c1-g5  �f8-d6,   9. �g3-h5  0-0,'+
                    '10. �d1-d2  �d8-e8,  11. g2-g4   �f6-g4,  12. �e5-g4  �e8-h5,'+
                    '13. �g4-e5  �b8-c6,  14. �f1-e2  �h5-h3,  15. �e5-c6  b7-c6,'+
                    '16. �g5-e3  �a8-b8,  17. 0-0-0   �f8-f2,  18. �e3-f2  �h3-a3,'+
                    '19. c2-c3   �a3-a2,  20. b2-b4   �a2-a1,  21. ��c1-c2 �a1-a4,'+
                    '22. ��c2-b2 �d6-b4,  23. c3-b4   �b8-b4,  24. �d2-b4  �a4-b4,'+
                    '25. ��b2-c2 e4-e3,   26. �f2-e3  �c8-f5,  27. �d1-d3  �b4-c4,'+
                    '28. ��c2-d2 �c4-a2,  29. ��d2-d1 �a2-b1.';
    b24.make_moves(b24.parser_record_game());  
    b24.link_events('#b24-begin','#b24-prev','#b24-next','#b24-last');
  }  

//������ �������� ��������� - �����, ������, 19 ���� 1858 ����, ������ ������ �����
  if($("#board25").length > 0){
    b25.board_f =b25.begin_position(); 
    b25.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  d7-d6,    3. d2-d4   e5-d4,'+
                     '4. �f3-d4  �g8-f6,   5. �b1-c3  �f8-e7,   6. �f1-e2  0-0,'+
                     '7. 0-0     c7-c5,    8. �d4-f3  �b8-c6,   9. �c1-f4  �c8-e6,'+
                    '10. �d1-d2  d6-d5,   11. e4-d5   �f6-d5,  12. �a1-d1  �d5-f4,'+
                    '13. �d2-f4  �d8-a5,  14. �e2-d3  �a8-d8,  15. �f3-g5  �e7-g5,'+
                    '16. �f4-g5  h7-h6,   17. �g5-h4  �c6-d4,  18. a2-a3   �f8-e8,'+
                    '19. �f1-e1  �a5-b6,  20. �c3-a4  �b6-a5,  21. �a4-c3  f7-f5,'+
                    '22. �e1-e5  �e6-f7,  23. �d1-e1  �a5-b6,  24. �e5-e8  �d8-e8,'+
                    '25. �e1-e8  �f7-e8,  26. �h4-e7  �e8-f7,  27. �c3-a4  �b6-a5,'+
                    '28. �a4-c5  �a5-d2,  29. f2-f3   �d4-c6,  30. �e7-e2  �d2-c1,'+
                    '31. ��g1-f2 �c1-b2,  32. �d3-f5  �b2-a3,  33. �e2-b5  �a3-c3,'+
                    '34. �c5-b3  �c3-f6,  35. �b5-b7  g7-g6,   36. �b7-c8  ��g8-h7,'+
                    '37. �f5-d3  �c6-e5,  38. �b3-d2  �f6-h4,  39. ��f2-f1 �h4-h2,'+
                    '40. �d2-e4  �h2-h1,  41. ��f1-f2 �h1-c1,  42. �c8-c3  �c1-f4,'+
                    '43. ��f2-e2 h6-h5,   44. �e4-f2  h5-h4,   45. �c3-d2  �f4-g3,'+
                    '46. �d2-e3  a7-a5,   47. �e3-e4  �f7-e6,  48. f3-f4   �e5-d3,'+
                    '49. c2-d3   �e6-g4,  50. ��e2-f1 �g4-f5,  51. �e4-e7  ��h7-h6.';
    b25.make_moves(b25.parser_record_game());  
    b25.link_events('#b25-begin','#b25-prev','#b25-next','#b25-last');
  }  

//���������� ����������� ������ ����� - ���������, ������, 20 ���� 1858 ����, ������ ������ �����
  if($("#board26").length > 0){
    b26.board_f =b26.begin_position(); 
    b26.record_game ='1. e2-e4   e7-e5,    2. f2-f4   �f8-c5,   3. �g1-f3  d7-d6,'+
                     '4. c2-c3   �c8-g4,   5. �f1-c4  �g4-f3,   6. �d1-f3  �g8-f6,'+
                     '7. b2-b4   �c5-b6,   8. d2-d3   �b8-d7,   9. f4-f5   �d8-e7,'+
                    '10. g2-g4   h7-h6,   11. ��e1-e2 c7-c6,   12. g4-g5   h6-g5,'+
                    '13. �c1-g5  d6-d5,   14. �c4-b3  �e7-d6,  15. �b1-d2  a7-a5,'+
                    '16. b4-a5   �a8-a5,  17. h2-h4   �f6-h5,  18. �d2-f1  �d7-c5,'+
                    '19. �b3-c2  �a5-b5,  20. �g5-c1  d5-e4,   21. d3-e4   �b5-b2,'+
                    '22. �c1-b2  �h5-f4,  23. ��e2-e1 �c5-d3,  24. �c2-d3  �f4-d3,'+
                    '25. ��e1-d2 �d3-b2,  26. ��d2-c2 �d6-a3,  27. �f1-d2  �b6-c7,'+
                    '28. �d2-b1.';
    b26.make_moves(b26.parser_record_game());  
    b26.link_events('#b26-begin','#b26-prev','#b26-next','#b26-last');
  }  

//������� ������ ��������� - �����, ������, 22 ���� 1858 ����, ������ ������ �����
  if($("#board27").length > 0){
    b27.board_f =b27.begin_position(); 
    b27.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �g8-f6,   3. �f3-e5  d7-d6,'+
                     '4. �e5-f3  �f6-e4,   5. d2-d4   d6-d5,    6. �f1-d3  �f8-e7,'+
                     '7. 0-0     �b8-c6,   8. �f1-e1  f7-f5,    9. c2-c4   �c8-e6,'+
                    '10. c4-d5   �e6-d5,  11. �b1-c3  �e4-c3,  12. b2-c3   0-0,'+
                    '13. �c1-f4  �e7-d6,  14. �f4-d6  �d8-d6,  15. �f3-e5  �a8-e8,'+
                    '16. c3-c4   �d5-e6,  17. �e5-c6  b7-c6,   18. �d3-f1  �e6-f7,'+
                    '19. �d1-d2  �e8-e1,  20. �a1-e1  �f8-d8,  21. �d2-a5  �d6-d4,'+
                    '22. �a5-c7  �d4-b6,  23. �c7-f4  g7-g6,   24. h2-h3   �b6-b2,'+
                    '25. �f4-c7  �b2-b6,  26. �e1-e7  �d8-d1,  27. �c7-c8  �d1-d8,'+
                    '28. �c8-c7  �d8-d1,  29. �c7-e5  �b6-b1,  30. �e5-e2  ��g8-f8,'+
                    '31. �e7-e5  f5-f4,   32. f2-f3   �b1-c1,  33. h3-h4   h7-h6,'+
                    '34. c4-c5   ��f8-g7, 35. �e5-e4  �c1-c5,  36. ��g1-h2 �c5-c1,'+
                    '37. ��h2-g1 �d1-d2,  38. �e2-a6  �d2-a2,  39. �a6-d3  �a2-d2,'+
                    '40. �d3-a6  �d2-d1,  41. g2-g3   f4-g3,   42. ��g1-g2 �c1-c5,'+
                    '43. ��g2-g3 �c5-g1,  44. �f1-g2  �d1-d2,  45. �a6-f1  �g1-f1,'+
                    '46. �g2-f1  ��g7-f6, 47. �f1-c4  �f7-c4,  48. �e4-c4  �d2-d6,'+
                    '49. ��g3-f4 �d6-e6,  50. �c4-d4  ��f6-e7, 51. �d4-a4  ��e7-d6,'+
                    '52. �a4-a7  c6-c5,   53. �a7-a1  c5-c4,   54. h4-h5   g6-h5,'+
                    '55. ��f4-f5 �e6-e3,  56. ��f5-f4 �e3-e8,  57. �a1-a6  ��d6-d5,'+
                    '58. �a6-h6  c4-c3,   59. �h6-h5  ��d5-d4, 60. �h5-h7  �e8-c8,'+
                    '61. �h7-d7  ��d4-c4, 62. ��f4-e3 �c8-e8,  63. ��e3-f2 c3-c2,'+
                    '64. �d7-c7  ��c4-d3, 65. �c7-d7  ��d3-c3, 66. �d7-c7  ��c3-d2,'+
                    '67. �c7-d7  ��d2-c1, 68. �d7-b7  �e8-e5,  69. f3-f4   �e5-e4,'+
                    '70. ��f2-f3 �e4-c4,  71. �b7-h7  ��c1-d2, 72. �h7-h1  c2-c1�,'+
                    '73. �h1-c1  �c4-c1,  74. ��f3-e4 �c1-e1,  75. ��e4-d4 ��d2-e2,'+
                    '76. f4-f5   ��e2-f3, 77. ��d4-d5 ��f3-f4, 78. f5-f6   ��f4-g5,'+
                    '79. f6-f7   �e1-f1,  80. ��d5-e6 ��g5-g6.';                    
    b27.make_moves(b27.parser_record_game());  
    b27.link_events('#b27-begin','#b27-prev','#b27-next','#b27-last');
    b27.link_events('#b27-2-begin','#b27-2-prev','#b27-2-next','#b27-2-last');    
  }  
  
//����������� ������, ���� - �����, ����� ������, ������ ��� ����� f7
  if($("#board32").length > 0){
    b32.board_f =b32.begin_position(); 
    b32.board_f['F'][7] ='';
    b32.record_game ='1. e2-e4   e7-e6,    2. d2-d4   d7-d5,    3. �f1-d3  g7-g6,'+
                     '4. �g1-f3  c7-c5,    5. c2-c3   �b8-c6,   6. 0-0     �d8-b6,'+
                     '7. e4-d5   e6-d5,    8. �f1-e1  �f8-e7,   9. �f3-g5  �g8-f6,'+
                    '10. �g5-h7  �h8-h7,  11. �d3-g6  �h7-f7,  12. �c1-g5  �c8-g4,'+
                    '13. �d1-c2  ��e8-f8, 14. �g6-f7  ��f8-f7, 15. h2-h3   �g4-h5,'+
                    '16. �g5-f6  �h5-g6,  17. �c2-e2  �e7-f6,  18. �e2-e6  ��f7-g7,'+
                    '19. �e6-d7  ��g7-h8, 20. �d7-d6  ��h8-g7, 21. �b1-d2  c5-d4,'+
                    '22. �d2-f3  �c6-e5,  23. �d6-a3  �e5-f3,  24. g2-f3   d4-c3,'+
                    '25. b2-c3   �a8-g8,  26. �e1-e3  ��g7-h8, 27. ��g1-h1 d5-d4,'+
                    '28. c3-d4   �f6-d4,  29. �a1-e1  �d4-e3,  30. �e1-e3  �b6-b1,'+
                    '31. ��h1-h2 �g6-e4,  32. �a3-c3  �g8-g7,  33. �e3-e1  �b1-b6,'+
                    '34. �e1-e3  �b6-d6,  35. ��h2-h1 �e4-f3,  36. �e3-f3  �d6-d1,'+
                    '37. ��h1-h2 �d1-g1.';                    
    b32.make_moves(b32.parser_record_game());  
    b32.link_events('#b32-begin','#b32-prev','#b32-next','#b32-last');
  }  
  
//������������ ������, ����� - �����, ����� ���� �������, ���������, 1858
  if($("#board006").length > 0){
    b006.board_f =b006.begin_position(); 
    b006.record_game ='1. e2-e4   c7-c5,    2. d2-d4   c5-d4,    3. �g1-f3  �b8-c6,'+
                      '4. �f3-d4  e7-e6,    5. �c1-e3  �g8-f6,   6. �f1-d3  d7-d5,'+
                      '7. �d4-c6  b7-c6,    8. e4-e5   �f6-d7,   9. f2-f4   �c8-a6,'+
                     '10. 0-0     �a6-d3,  11. �d1-d3  �f8-c5,  12. �b1-d2  �c5-e3,'+
                     '13. �d3-e3  �d8-b6,  14. �a1-e1  0-0,     15. b2-b3   f7-f6,'+
                     '16. e5-f6   �f8-f6,  17. g2-g3   �a8-f8,  18. ��g1-g2 �b6-e3,'+
                     '19. �e1-e3  g7-g6,   20. �f1-e1  e6-e5,   21. �e1-e2  e5-f4,'+
                     '22. �e3-e7  �f8-f7,  23. g3-f4   �f6-f4,  24. �e7-e8  ��g8-g7,'+
                     '25. �e8-c8  �f4-f6,  26. �c8-c7  �d7-f8,  27. �e2-e7  �f7-e7,'+
                     '28. �c7-e7  �f6-f7,  29. �e7-e8  �f8-d7,  30. �d2-f3  �f7-f8,'+
                     '31. �e8-e7  �f8-f7,  32. �e7-e8  �f7-f8.';                    
    b006.make_moves(b006.parser_record_game());  
    b006.link_events('#b006-begin','#b006-prev','#b006-next','#b006-last');
  }  
  
//������ ��������, �����-�������, ��������� ������, ����� 1858 ���
  if($("#board34").length > 0){
    b34.board_f =b34.begin_position(); 
    b34.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  d7-d6,    3. d2-d4   e5-d4,'+
                     '4. �d1-d4  �b8-c6,   5. �f1-b5  �c8-d7,   6. �b5-c6  �d7-c6,'+
                     '7. �c1-g5  f7-f6,    8. �g5-h4  �g8-h6,   9. �b1-c3  �d8-d7,'+
                    '10. 0-0     �f8-e7,  11. �a1-d1  0-0,     12. �d4-c4  �f8-f7,'+
                    '13. �f3-d4  �h6-g4,  14. h2-h3   �g4-e5,  15. �c4-e2  g7-g5,'+
                    '16. �h4-g3  �f7-g7,  17. �d4-f5  �g7-g6,  18. f2-f4   g5-f4,'+
                    '19. �f1-f4  ��g8-h8, 20. �f4-h4  �e7-f8,  21. �g3-e5  f6-e5,'+
                    '22. �d1-f1  �d7-e6,  23. �c3-b5  �e6-g8,  24. �f1-f2  a7-a6,'+
                    '25. �b5-c7  �a8-c8,  26. �c7-d5  �c6-d5,  27. e4-d5   �c8-c7,'+
                    '28. c2-c4   �f8-e7,  29. �h4-h5  �g8-e8,  30. c4-c5   �c7-c5,'+
                    '31. �h5-h7  ��h8-h7, 32. �e2-h5  ��h7-g8, 33. �f5-e7  ��g8-g7,'+
                    '34. �e7-f5  ��g7-g8, 35. �f5-d6.';                    
    b34.make_moves(b34.parser_record_game());  
    b34.link_events('#b34-begin','#b34-prev','#b34-next','#b34-last');
  }  
  
//����������� ������, �������-�����, ������� ������, ����� 1858 ���
  if($("#board37").length > 0){
    b37.board_f =b37.begin_position(); 
    b37.record_game ='1. d2-d4   f7-f5,    2. c2-c4   e7-e6,    3. �b1-c3  �g8-f6,'+
                     '4. �c1-g5  �f8-e7,   5. e2-e3   0-0,      6. �f1-d3  b7-b6,'+
                     '7. �g1-e2  �c8-b7,   8. �g5-f6  �e7-f6,   9. 0-0     �d8-e7,'+
                    '10. �d1-d2  d7-d6,   11. f2-f4   c7-c5,   12. d4-d5   �b8-a6,'+
                    '13. d5-e6   �e7-e6,  14. �a1-e1  �f6-h4,  15. �e2-g3  �e6-g6,'+
                    '16. �c3-d5  �b7-d5,  17. c4-d5   �h4-g3,  18. h2-g3   �a6-c7,'+
                    '19. ��g1-f2 �a8-e8,  20. �f1-h1  �e8-e7,  21. �h1-h4  �g6-f7,'+
                    '22. �d3-e2  �c7-e8,  23. �d2-d3  �e8-f6,  24. �e2-f3  g7-g6,'+
                    '25. �e1-e2  �f8-e8,  26. b2-b3   �f7-g7,  27. �h4-h1  h7-h6,'+
                    '28. ��f2-g1 g6-g5,   29. f4-g5   h6-g5,   30. �f3-h5  �f6-e4,'+
                    '31. �e2-e1  �e8-f8,  32. �h5-f3  �e4-g3,  33. �h1-h3  �g7-e5,'+
                    '34. �h3-h6  g5-g4,   35. �f3-d1  ��g8-g7, 36. �h6-h4  �f8-h8,'+
                    '37. �h4-h8  ��g7-h8, 38. �d1-c2  �e7-h7,  39. �d3-d2  �e5-b2,'+
                    '40. �e1-d1  �h7-h1,  41. ��g1-f2 �h1-f1,  42. ��f2-g3 �b2-e5,'+
                    '43. ��g3-h4 �e5-f6,  44. ��h4-g3 �f6-e5,  45. ��g3-h4 �e5-f6.';                    
    b37.make_moves(b37.parser_record_game());  
    b37.link_events('#b37-begin','#b37-prev','#b37-next','#b37-last');
  }
  
//��������� ������, ����� - ��������, ������ ������, �����, 22 ������� 1858 ����
  if($("#board40").length > 0){
    b40.board_f =b40.begin_position(); 
    b40.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �f1-b5  �g8-f6,'+
                     '4. d2-d4   �c6-d4,   5. �f3-d4  e5-d4,    6. e4-e5   c7-c6,'+
                     '7. 0-0     c6-b5,    8. �c1-g5  �f8-e7,   9. e5-f6   �e7-f6,'+
                    '10. �f1-e1  ��e8-f8, 11. �g5-f6  �d8-f6,  12. c2-c3   d7-d5,'+
                    '13. c3-d4   �c8-e6,  14. �b1-c3  a7-a6,   15. �e1-e5  �a8-d8,'+
                    '16. �d1-b3  �f6-e7,  17. �a1-e1  g7-g5,   18. �b3-d1  �e7-f6,'+
                    '19. �e1-e3  �h8-g8,  20. �e5-e6.';
    b40.make_moves(b40.parser_record_game());  
    b40.link_events('#b40-begin','#b40-prev','#b40-next','#b40-last');
  }  
  
//������������ ������, ����� - ����������, ������ ������, �����, 1859 ���.
  if($("#board52").length > 0){
    b52.board_f =b52.begin_position(); 
    b52.record_game = '1.  e2-e4   e7-e5,   2. �g1-f3  d7-d5,   3. e4-d5   e5-e4,'+
                      '4.  �d1-e2  �d8-e7,  5. �f3-d4  �e7-e5,  6. �d4-b5  �f8-d6,'+
                      '7.  d2-d4   �e5-e7,  8. c2-c4   �d6-b4,  9. �c1-d2  �b4-d2,'+
                      '10. �b1-d2  a7-a6,  11. �b5-c3  f7-f5,  12. 0-0-0   �g8-f6,'+
                      '13. �d1-e1  0-0,    14. f2-f3   b7-b5,  15. f3-e4   f5-e4,'+
                      '16. �d2-e4  b5-c4,  17. �e2-c4  ��g8-h8,18. �f1-d3  �c8-b7,'+
                      '19. �e4-f6  �e7-f6, 20. �h1-f1  �f6-d8, 21. �f1-f8  �d8-f8,'+
                      '22. �c4-b4.';
    b52.make_moves(b52.parser_record_game());  
    b52.link_events('#b52-begin','#b52-prev','#b52-next','#b52-last');
  }  

//������ ��������, ����� - ������ ���� ��������������+���� �����, �����, 1858
  if($("#board55").length > 0){
    b55.board_f =b55.begin_position(); 
    b55.record_game ='1. e2-e4   e7-e5,    2. �g1-f3   d7-d6,    3. d2-d4  �c8-g4,'+
                     '4. d4-e5   �g4-f3,   5. �d1-f3   d6-e5,    6. �f1-c4 �g8-f6,'+
                     '7. �f3-b3  �d8-e7,   8. �b1-c3   c7-c6,    9. �c1-g5 b7-b5,'+
                    '10. �c3-b5  c6-b5,   11. �c4-b5   �b8-d7,  12. 0-0-0  �a8-d8,'+
                    '13. �d1-d7  �d8-d7,  14. �h1-d1   �e7-e6,  15. �b5-d7 �f6-d7,'+
                    '16. �b3-b8  �d7-b8,  17. �d1-d8.';
    b55.make_moves(b55.parser_record_game());  
    b55.link_events('#b55-begin','#b55-prev','#b55-next','#b55-last');
  }  

//������ � ����-����� �����, ������, 26 ������ 1859 ���
//����� ���� �����, �� ������ - �����
  if($("#board014").length > 0){
    b014.board_f =b014.begin_position(); 
    b014.record_game ='1.  e2-e4   e7-e5,   2. �g1-f3  �b8-c6,  3. �f1-c4  �g8-f6,'+
                      '4.  �f3-g5  d7-d5,   5. e4-d5   �c6-a5,  6. d2-d3   h7-h6,'+
                      '7.  �g5-f3  e5-e4,   8. �d1-e2  �a5-c4,  9. d3-c4   �f8-c5,'+
                      '10. h2-h3   0-0,    11. �f3-h2  �f6-h7, 12. �b1-c3  f7-f5,'+
                      '13. �c1-e3  �c5-b4, 14. �e2-d2  �c8-d7, 15. g2-g3   �d8-e7,'+
                      '16. a2-a3   �b4-d6, 17. �c3-e2  b7-b5,  18. c4-b5   �d7-b5,'+
                      '19. �e2-d4  �b5-c4, 20. �d4-e6  �f8-e8, 21. �d2-d4  �c4-a6,'+
                      '22. c2-c4   c7-c5,  23. �d4-c3  �a6-c8, 24. �e6-f4  �a8-b8,'+
                      '25. �a1-b1  g7-g5,  26. �f4-e2  �h7-f8, 27. h3-h4   �f8-g6,'+
                      '28. h4-g5   h6-g5,  29. �c3-c1  �g6-e5, 30. �e3-g5  �e5-d3,'+
                      '31. ��e1-f1 �e7-g7, 32. �c1-d2  �d3-b2, 33. �d2-c2  �c8-a6,'+
                      '34. �g5-c1  �b2-c4, 35. �c2-a4  �c4-d2, 36. ��f1-g2 �d2-b1,'+
                      '37. �a4-a6  �b8-b6, 38. �a6-a4  �e8-b8, 39. �h2-f1  �d6-e5,'+
                      '40. �f1-e3  f5-f4,  41. �e2-f4  �e5-f4, 42. �e3-f5  �g7-f7,'+
                      '43. �c1-f4  �f7-f5, 44. �f4-b8  �b6-b8, 45. �a4-a7  �b8-f8,'+
                      '46. �a7-c5  �f5-f3, 47. ��g2-g1 �b1-c3, 48. �h1-h4  �c3-e2,'+
                      '49. ��g1-h2 �f3-f2, 50. �c5-f2  �f8-f2, 51. ��h2-h3 �e2-g1,'+
                      '52. ��h3-g4 e4-e3,  53. ��g4-h5 e3-e2,  54. �h4-e4  �f2-f1.';
    b014.make_moves(b014.parser_record_game());  
    b014.link_events('#b014-begin','#b014-prev','#b014-next','#b014-last');
  }  

//������ � ����-����� �����, ������, 26 ������ 1859 ���
//��������� ������, ��������� - �����
  if($("#board016").length > 0){
    b016.board_f =b016.begin_position(); 
    b016.record_game ='1.  e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �f1-b5  a7-a6,'+
                      '4.  �b5-a4  �g8-f6,   5. 0-0     �f8-e7,   6. d2-d4   e5-d4,'+
                      '7.  e4-e5   �f6-e4,   8. �a4-c6  d7-c6,    9. �d1-d4  �c8-f5,'+
                      '10. �b1-c3  �e7-c5,  11. �d4-d8  �a8-d8,  12. �f3-h4  �e4-c3,'+
                      '13. �h4-f5  �c3-e2,  14. ��g1-h1 g7-g6,   15. �f5-g3  �e2-g3,'+
                      '16. h2-g3   h7-h6,   17. �a1-b1  ��e8-e7, 18. b2-b4   �c5-d4,'+
                      '19. f2-f4   ��e7-e6, 20. �b1-b3  h6-h5,   21. �b3-d3  �d4-b6,'+
                      '22. �f1-d1  �d8-d3,  23. �d1-d3  ��e6-f5, 24. �c1-b2  �h8-h7,'+
                      '25. �b2-d4  h5-h4,   26. �d4-b6  h4-g3,   27. ��h1-g1 c7-b6,'+
                      '28. �d3-d7  ��f5-e6, 29. �d7-b7  �h7-h4,  30. �b7-b6  �h4-f4,'+
                      '31. �b6-c6  ��e6-e5, 32. �c6-c5  ��e5-d6, 33. �c5-g5  �f4-b4,'+
                      '34. �g5-g3  �b4-a4,  35. a2-a3   �a4-c4,  36. �g3-d3  ��d6-e6,'+
                      '37. �d3-b3  �c4-c2,  38. �b3-b6  ��e6-f5, 39. �b6-a6  g6-g5,'+
                      '40. �a6-b6  �c2-a2,  41. �b6-b3  g5-g4,   42. �b3-b5  ��f5-f4,'+
                      '43. �b5-b3  f7-f5,   44. g2-g3   ��f4-e4, 45. ��g1-f1 ��e4-e5,'+
                      '46. ��f1-g1 f5-f4,   47. �b3-b4.';
    b016.make_moves(b016.parser_record_game());  
    b016.link_events('#b016-begin','#b016-prev','#b016-next','#b016-last');
  }
  
//������ ��������, ����� - �������, ���-����, ��� 1859 (����� ��� ���� b1)
  if($("#board57").length > 0){
    b57.board_f =b57.begin_position(); 
    b57.board_f['B'][1] ='';
    b57.record_game ='1. e2-e4  e7-e5,   2. �g1-f3  d7-d6,   3. d2-d4  e5-d4,'+
                     '4. �f1-c4 �b8-c6,  5. c2-c3   �c6-e5,  6. �f3-e5 d6-e5,'+
                     '7. �d1-b3 �d8-e7,  8. f2-f4   d4-c3,   9. 0-0    c7-c6,'+
                    '10. f4-e5  �c8-e6, 11. �c4-e6  f7-e6,  12. �c1-g5 �e7-g5,'+
                    '13. �b3-b7 �f8-c5, 14. ��g1-h1 �g5-e5, 15. �b7-a8 ��e8-d7,'+
                    '16. �a8-b7 �e5-c7, 17. �a1-d1  �c5-d6, 18. �d1-d6 ��d7-d6,' +
                    '19. �f1-d1.';
    b57.make_moves(b57.parser_record_game());  
    b57.link_events('#b57-begin','#b57-prev','#b57-next','#b57-last');
  }  
  
//����������� ������, ����� - ������, ���-����, 11 ��� 1859 (����� ��� ���� b1)
  if($("#board58").length > 0){
    b58.board_f =b58.begin_position(); 
    b58.board_f['B'][1] ='';
    b58.record_game ='1. e2-e4   e7-e5,   2. �g1-f3  �b8-c6,   3. d2-d4  e5-d4,'+
                     '4. �f1-c4  �f8-c5,  5. 0-0     d7-d6,    6. b2-b4  �c5-b6,'+
                     '7. b4-b5   �c6-e5,  8. �f3-e5  d6-e5,    9. f2-f4  d4-d3,'+
                    '10. ��g1-h1 �d8-d4, 11. �c4-f7  ��e8-f7, 12. f4-e5  ��f7-e8,'+
                    '13. �d1-f3  �g8-e7, 14. �f3-f7  ��e8-d8, 15. �c1-g5 �d4-e5,'+
                    '16. �a1-d1  �e5-g5, 17. �d1-d3  �c8-d7,  18. �f7-f8 �h8-f8,' +
                    '19. �f1-f8.';
    b58.make_moves(b58.parser_record_game());  
    b58.link_events('#b58-begin','#b58-prev','#b58-next','#b58-last');
  }  

//����������� ������, ����� - �� ������, �����, ������ 1863
  if($("#board63").length > 0){
    b63.board_f =b63.begin_position(); 
    b63.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �f1-c4  �f8-c5,'+
                     '4. c2-c3   �d8-e7,   5. d2-d4   �c5-b6,   6. 0-0     d7-d6,'+
                     '7. h2-h3   �g8-f6,   8. �f1-e1  h7-h6,    9. a2-a4   a7-a5,'+
                    '10. �b1-a3  �c6-d8,  11. �a3-c2  �c8-e6,  12. �c2-e3  �e6-c4,'+
                    '13. �e3-c4  �f6-d7,  14. �c4-e3  g7-g6,   15. �e3-d5  �e7-e6,'+
                    '16. �c1-h6  f7-f6,   17. �h6-g7  �h8-h5,  18. g2-g4   �h5-h3,'+
                    '19. �d5-f6  �d7-f6,  20. �f3-g5  �e6-d7,  21. �g7-f6  �h3-h4,'+
                    '22. f2-f3   e5-d4,   23. c3-d4   �h4-h6,  24. ��g1-g2 �d8-f7,'+
                    '25. �e1-h1  �f7-g5,  26. �h1-h6  �g5-h7,  27. �d1-h1  �h7-f6,'+
                    '28. �h6-h8  ��e8-e7, 29. �h8-a8  �b6-d4,  30. �h1-h6  �d7-c6,'+
                    '31. �a1-c1  �c6-b6,  32. �c1-c7  ��e7-e6, 33. �a8-e8  �f6-e8,'+
                    '34. �h6-g6  ��e6-e5, 35. �g6-f5.';
    b63.make_moves(b63.parser_record_game());  
    b63.link_events('#b63-begin','#b63-prev','#b63-next','#b63-last');
  }  

//����� ���� �����, �� ������ - �����, �����, ������ 1863
  if($("#board64").length > 0){
    b64.board_f =b64.begin_position(); 
    b64.record_game ='1. e2-e4   e7-e5,    2. �g1-f3  �b8-c6,   3. �f1-c4  �g8-f6,'+
                     '4. �f3-g5  d7-d5,    5. e4-d5   �c6-a5,   6. d2-d3   h7-h6,'+
                     '7. �g5-f3  e5-e4,    8. �d1-e2  �a5-c4,   9. d3-c4   �f8-c5,'+
                    '10. h2-h3   0-0,     11. �f3-h2  �f6-h7,  12. �b1-d2  f7-f5,'+
                    '13. �d2-b3  �c5-d6,  14. 0-0     �d6-h2,  15. ��g1-h2 f5-f4,'+
                    '16. �e2-e4  �h7-g5,  17. �e4-d4  �g5-f3,  18. g2-f3   �d8-h4,'+
                    '19. �f1-h1  �c8-h3,  20. �c1-d2  �f8-f6.';
    b64.make_moves(b64.parser_record_game());  
    b64.link_events('#b64-begin','#b64-prev','#b64-next','#b64-last');
  }  

//������ ����, ����� - ������, 1869 (����� ��� ���� b1)
  if($("#board68").length > 0){
    b68.board_f =b68.begin_position(); 
    b68.board_f['B'][1] ='';
    b68.record_game ='1. e2-e4   e7-e5,   2. f2-f4   e5-f4,    3. �g1-f3 g7-g5,'+
                     '4. �f1-c4  g5-g4,   5. d2-d4   g4-f3,    6. �d1-f3 d7-d6,'+
                     '7. 0-0     �c8-e6,  8. d4-d5   �e6-c8,   9. �c1-f4 �d8-d7,'+
                    '10. e4-e5   �d7-g4, 11. �f3-e3  �f8-e7,  12. e5-d6  c7-d6,'+
                    '13. �a1-e1  h7-h5,  14. �f4-d6  �g4-d7,  15. �d6-e7 �g8-e7,'+
                    '16. �c4-b5.';
    b68.make_moves(b68.parser_record_game());  
    b68.link_events('#b68-begin','#b68-prev','#b68-next','#b68-last');
  }  
  
//����� �����, ����� - ������, 1869 (����� ��� ���� b1)
  if($("#board69").length > 0){
    b69.board_f =b69.begin_position(); 
    b69.board_f['B'][1] ='';
    b69.record_game ='1. f2-f4   e7-e6,   2. �g1-f3  f7-f5,    3. e2-e3   �g8-f6,'+
                     '4. �f1-e2  �f8-e7,  5. 0-0     b7-b6,    6. b2-b3   0-0,'+
                     '7. �c1-b2  �c8-b7,  8. h2-h3   h7-h6,    9. ��g1-h2 c7-c5,'+
                    '10. �f1-g1  �b8-c6, 11. g2-g4   f5-g4,   12. h3-g4   �d8-c7,'+
                    '13. g4-g5   h6-g5,  14. �f3-g5  �c6-d4,  15. �e2-d3  �d4-f5,'+
                    '16. �d1-f1  g7-g6,  17. �f1-h3  �f8-f7,  18. �g5-f7  ��g8-f7,'+
                    '19. �d3-f5  e6-f5,  20. �b2-f6  �e7-f6,  21. �h3-h7  ��f7-e6,'+
                    '22. �g1-g6  �c7-d8, 23. �a1-g1  ��e6-d6, 24. �g6-f6  ��d6-c7,'+
                    '25. �f6-f7  �b7-c6, 26. �g1-g7  �d8-e8,  27. �h7-f5  �e8-h8,'+
                    '28. �g7-h7.';
    b69.make_moves(b69.parser_record_game());  
    b69.link_events('#b69-begin','#b69-prev','#b69-next','#b69-last');
  }  
  

})


