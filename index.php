<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Nam linh Chi Nagao bao ve suc khoe - lam dep cuoc song</title>
<link rel="alternate" href="http://www.namlinhchinagao.com" hreflang="vi-vn" />
<meta name="description" content="Uong nam linh chi moi ngay de bao ve suc khoe cua ban - nam linh chi day lui benh tat, ngan ngua ung thu, tri huyet ap cao, benh tieu duong; keo dai tuoi xuan." />
<meta http-equiv="content-language" content="vi"/>
<link rel="canonical" href="http://www.namlinhchinagao.com" />

<meta property="og:url" content="http://www.namlinhchinagao.com" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Nam linh Chi Nagao bao ve suc khoe - lam dep cuoc song" />
<meta property="og:description" content="Uong nam linh chi moi ngay de bao ve suc khoe cua ban - nam linh chi day lui benh tat, ngan ngua ung thu, tri huyet ap cao, benh tieu duong; keo dai tuoi xuan." />

<meta property="og:image" content="http://www.namlinhchinagao.com/picture/linhchi-nagao.jpg" />

<meta name="robots" content="index,follow" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/Home.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
<script src="javascript/common.js" type="text/javascript" > </script>
<script src="javascript/home.js" type="text/javascript" > </script>
</head>

<body>
	<div id="CenterDirection">
		<span><a href="/don-hang/" title="Dat mua nam linh chi NAGAO" id="don-hang01">ĐẶT HÀNG</a></span>
		<a href="/cua-hang-linh-chi/" title="Nam linh chi bao ve suc khoe cua ban" id="cua-hang01">Sản Phẩm<span id="LAInCenter">></span></a>	
	</div>

<?php include "php/header.php"; ?>

	<!--  Phần thân-->
	<h1 title="nam linh chi NAGAO bao ve suc khoe cua ban">NẤM LINH CHI NAGAO</h1>
	<div id="SlideFrame" class="col11">
		<div id="slider">
		<figure>
			<img src="picture/slide/BannerLinhchi.jpg" alt="Nam linh chi ngan ngua ung thu - bao ve suc khoe"/>
			<img src="picture/slide/ReishiandTea.jpg" alt="reishi and Tea"/>
			<img src="picture/slide/supervise.jpg" alt="Giám sát quá trình phát triển của nấm"/>
			<img src="picture/slide/InsideFarm.jpg" alt="Nong Trai nam linh chi NAGAO"/>
			<img src="picture/slide/BannerLinhchi.jpg" alt="Nam linh chi ngan ngua ung thu - bao ve suc khoe"/>
		</figure>

		</div>
	</div>
	
<?php 
require_once (dirname(__FILE__) . '/php/constant.php');
$servername = DATASERVER;
$dbname = DATABASE;

try{
	$db = new PDO("mysql:host=$servername;dbname=$dbname", USER, PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)	{
	echo "Could not connect: " . $e->getMessage();
}
if($db){	
	$order_query = "SELECT nt.titolo, nt.friendly_url, nt.immagine FROM `news_testi` nt WHERE nt.news_approvata = 1 AND nt.data_pubb < " . time() . " ORDER BY nt.data_pubb DESC LIMIT 0,4";
	try{	
		$stmt = $db->prepare($order_query);
		$stmt->execute();
		$q_order = $stmt->fetchall();
	}
	catch(PDOException $e){
		echo "Cannot get Data to display:" . $e->getMessage();
	}



}
?>	
	<div id="ListBigIcon" class="col11">
		<div><a href="news/<?php echo $q_order[0]['friendly_url'];  ?>/" id="news01"><img src="<?php echo $q_order[0]['immagine'];  ?>" alt="<?php echo $q_order[0]['titolo'];  ?>" title="<?php echo $q_order[0]['titolo'];  ?>" /></a></div> 
		<div><a href="news/<?php echo $q_order[1]['friendly_url'];  ?>/" id="news02"><img src="<?php echo $q_order[1]['immagine'];  ?>" alt="<?php echo $q_order[1]['titolo'];  ?>" title="<?php echo $q_order[1]['titolo'];  ?>" /></a> </div>
		<div><a href="news/<?php echo $q_order[2]['friendly_url'];  ?>/" id="news03"><img src="<?php echo $q_order[2]['immagine'];  ?>" alt="<?php echo $q_order[2]['titolo'];  ?>" title="<?php echo $q_order[2]['titolo'];  ?>"  /></a></div> 
		<div><a href="news/<?php echo $q_order[3]['friendly_url'];  ?>/" id="news04"><img src="<?php echo $q_order[3]['immagine'];  ?>" alt="<?php echo $q_order[3]['titolo'];  ?>" title="<?php echo $q_order[3]['titolo'];  ?>" /></a></div> 
		
	</div>
	<div id="BriefGuideMenu" class="col11">
		<a class="buttonFAQ activetag" data-filter="Effect" id="more-info01">Hiệu quả</a>
		<a class="buttonFAQ" data-filter="Constituent" id="more-info02">Dược chất</a>
		<a class="buttonFAQ" data-filter="Side-Effect" id="more-info03">Tác dụng phụ</a>
		<a class="buttonFAQ" data-filter="Contraindication" id="more-info04">Chống chỉ định</a>
		<a class="buttonFAQ" data-filter="Interaction" id="more-info05">Tương tác thuốc</a>
		<a class="buttonFAQ" data-filter="Use" id="more-info06">Sử dụng</a>
		<a class="buttonFAQ" data-filter="Toxicology" id="more-info07">Ngộ độc</a>
		<a class="buttonFAQ" data-filter="Prenancy-Lactation" id="more-info08">Phụ nữ có thai và cho con bú</a>
		<a class="buttonFAQ" data-filter="Reference" id="more-info09">Tài liệu nghiên cứu</a>
	</div>
	<div id="BriefGuide" class="col11">
		<div data-cat="Effect">
			<h2>Giới thiệu chung nấm linh chi</h2>
			<div>
			<p>Nấm linh chi là một loại nấm có bào tử màu nâu, tai nấm hình quạt, bóng loáng. Nấm linh chi mọc trên gỗ mục hoặc các gốc cây, tìm thấy nhiều ở cây mận Nhật Bản, và một ít tại cây sồi. Nấm linh chi có nguồn gốc từ Trung Quốc, Nhật Bản và Bắc Mỹ nhưng cũng được trồng nhiều  ở các quốc gia Châu Á khác. Quá trình thu hoạch nấm linh chi khá dài và phức tạp. Nấm linh chi có 6 màu, mỗi loại có những đặc tính khác nhau: Aoshiba ( nấm linh chi xanh ), Akashiba ( nấm linh chi đỏ ), Kishiba ( nấm linh chi vàng ), Shiroshiba ( nấm linh chi trắng ), Kuroshiba ( nấm linh chi đen ), và Murasakishiba ( nấm linh chi tía).</p>

<p>Nấm linh chi được sử dụng để tăng sức mạnh của hệ thống miễn dịch, tang cuong <b>suc khoe</b>; chống lại các bệnh nhiễm virus như cúm, cúm heo, cúm chim; Các bệnh về phổi như hen xuyễn, viêm phế quản; Bệnh tim và các triệu chứng như huyết áp cao, cholesterol cao; Rối loạn thần kinh do stress; <b>Ung thu</b>; và các bệnh về gan. Nấm linh chi cũng được sử dụng trong điều trị HIV/AIDS, bệnh sợ độ cao, hội chứng mệt mỏi kéo dài (CFS), bệnh mất ngủ, loét dạ dày, nhiễm độc và bệnh mụn giộp. Các sử dụng khác nấm linh chi nhằm giảm stress và ngăn chặn mệt mỏi, keo dai tuoi tho.</p>

<p>Được sử dụng kết hợp với các thảo dược khác, nấm linh chi dùng để chữa bệnh ung thư tuyến tiền liệt</p>
		
			</div>
			<div>

<h3>Ung thu</h3>

<p>Các hiệu quả tiêu diệt <b>ung thu</b> của nấm linh chi được báo cáo rộng rãi từ các thí nghiệm được công bố. Ý kiến được chấp nhận rộng rãi là các tac dung tiêu diệt ung thư có được bởi hệ thống miễn dịch được tăng cường, và đến từ các thành phần hoạt chất khác nhau của nấm linh chi. Các thí nghiệm tập trung vào hiệu quả kích thích của lượng polysaccharide lớn hơn lên hệ miễn dịch, và hiệu quả ngăn chặn của triterpene lên sự phát triển và xâm lấn của tế bào ung thu.</p>

<h3>Dữ liệu điều trị</h3>

<p>Các thí nghiệm được tiến hành với các bệnh nhân ung thư nặng. Ganopoly (thành phần của polysaccharide) được sử dụng với liều lượng 5.4 g mỗi ngày ( tương đương với 81 gram nấm) liên tục trong 12 tuần. Các chỉ số của hệ thống miễn dịch gia tăng được báo cáo đối với 80% số bệnh nhân ung thư ở một thí nghiệm, chất lượng sức sống cải thiện ở 65% bệnh nhân ở một thí nghiệm khác. Trong một thí nghiệm sâu hơn, nhiều kết quả đạt được. Chất ganopoly được cho rằng có thể đảo ngược hiệu ứng ngăn chặn hệ thống miễn dịch của hoá trị liệu và tia X trị liệu.</p>			
			</div>
			<div>
<h3>Các hiệu quả tim mạch</h3>

<p>Các tac dung cua nam linh chi lên hệ thống tim mạch được điều tra. Hiệu quả giảm huyết áp được báo cáo và được cho là do các ganoderic axit. Chất Triterpenes được cho rằng đã ngăn chặn các enzyme chuyển đổi Angiotensin. Sự ngăn chặn tổng hợp cholesterol, nâng cao hoạt động chống oxy hoá, giảm kết tụ tế bào tiểu huyết cầu và giảm quá trình peoroxy chất béo được báo cáo trong các thí nghiệm.</p>

<h3>Benh tieu duong</h3>

<p>Trong các thí nghiệm với động vật, Ganopoly tác động đến  sự trao đổi carbon hydrate và thúc đẩy sự tạo thành insulin. Trong một cuộc thí nghiệm với các bệnh nhân tiểu đường cấp độ 2, ganopoly 1800 mg được sử dụng trong 3 ngày đã giảm lượng đường sau mỗi buổi ăn. Thành phần ganoderan A, B trong nấm linh chi ngăn cản quá trình tạo đường được xác nhận ở các thí nghiệm.</p>

			</div>
			<div>
<h3>Bệnh viêm gan</h3>

<p>Trong các thí nghiệm trên động vật, các chiết xuất từ nấm linh chi cho thấy đã chống lại các tổn thương gan. </p>

<h3>Các bệnh thấp khớp</h3>

<p>Các tac dung cua nam linh chi trên hệ thống miễn dịch đã được ghi nhận từ các thí nghiệm. Tại một thí nghiệm trên  chất lỏng hoạt dịch từ một bệnh nhân bệnh thấp khớp, các nhà nghiên cứu xác định hiệu quả ngăn chặn của polysaccharide trên sự sinh sôi của các tế bào sợi trong chất hoạt dịch.</p>			
			</div>
		</div>
		<div data-cat="Constituent">
			<h2>Tại sao nấm linh chi có tác dụng như vậy?</h2>
			<div>Nấm linh chi chứa các hoạt chất mà dường như có rất nhiều hiệu quả, gồm những hoạt động chống lại các khối u và các hiệu ứng có lợi trên hệ thống miễn dịch. Các hoạt chất được kể đó là polysaccharide với ít nhất 36 hợp chất được xác định, gồm beta-d-glucan và GL-1. Triterpene, bao gồm ganoderic axit A, B, C, D; ganoderol A, B; ganoderol A; lucidenic B, và ganodermanontriol. Terpenoid 1, 2 và 3 và terpene lucidenic axit O và lucidenic lactone cũng hiện diện. Một số enzyme, khoáng chất như canxi, ma-giê, kali cũng được báo cáo. Các chất Lanostan, coumarins, ergosterol và cerevisterol cũng có trong nấm linh chi.</div>
		</div>
		<div data-cat="Side-Effect">
			<h2>Tác dụng phụ</h2>
			<div>
			<p>Các tác dụng phụ được ghi nhận trong thời gian đầu sử dụng linh chi gồm chóng mặt, khô miệng, mệt bao tử, chảy máu mũi, đau nhức, mẫn ngứa da, tiêu chảy và chứng táo bón.</p>

<p>Các tác dụng phụ này chỉ xảy ra trên một số ít người có thần kinh mẫn cảm và tự hết sau 4 tuần sử dụng.</p>
			</div>
		</div>
		<div data-cat="Contraindication">
			<h2>Chống chỉ định</h2>
			<div>Các chống chỉ định chưa được xác định</div>
		
		</div>	
		<div data-cat="Interaction">
			<h2>Tương tác với thuốc</h2>
			<div>
<p><b>Không có tài liệu nghiên cứu uy tín về sự tương tác với thuốc của nấm linh chi.</b></p>

<p>Dựa trên một số tài liệu kém tin cậy, thì do nấm linh chi làm chậm quá trình đông máu nên việc sử dụng nấm linh chi kèm với thuốc có tác dụng chậm đông máu sẽ khiến gia tăng khả năng bị thâm tím cơ thể và bị chảy máu.</p>
<p>Các loại thuốc làm chậm đông máu bao gồm: aspirin, clopidogrel (Plavix), diclofenac (Voltaren, Cataflam, others), ibuprofen (Advil, Motrin, others), naproxen (Anaprox, Naprosyn, others), dalteparin (Fragmin), enoxaparin (Lovenox), heparin, warfarin (Coumadin), ...</p>

<p>Một số tài liệu kém tin cậy khác khuyến cáo việc sử dụng chung nấm linh chi với thuốc giảm cao huyết áp vì nó có thể khiến hiệu ứng giảm huyết áp mạnh hơn.</p>
<p>Các thuốc giảm huyết áp bao gồm: captopril (Capoten), enalapril (Vasotec), losartan (Cozaar), valsartan (Diovan), diltiazem (Cardizem), Amlodipine (Norvasc), hydrochlorothiazide (HydroDIURIL), furosemide (Lasix)...		</p>	
			
			</div>
		
		</div>	
		<div data-cat="Use">
			<h2>Sử dụng</h2>
			<div>
Các bác sỹ đa khoa đề nghị mức 0,5 - 1 g tinh chất( tương đương 7 - 15 g nấm linh chi ) mỗi ngày cho các trường hợp thông thường, 2 - 5 g ( tương đương 30 - 75 g nấm linh chi ) cho trường hợp bệnh nặng và lên đến 15g ( tương đương 225g nấm linh chi) mỗi ngày cho trường hợp bệnh nghiêm trọng. Sách dược phẩm Trung Quốc đề nghị mức 6-12g ( tương đương 90 - 180g nấm linh chi) mỗi ngày. 			
			</div>
		
		</div>	
		<div data-cat="Toxicology">
			<h2>Ngộ độc</h2>
			<div>
			Các nghiên cứu đã làm sáng tỏ thông tin về ngộ độc nấm linh chi. Liều lượng gây chết người của nấm linh chi được ước đoán là 10-21g tinh chất ( tương đương 150- 315g nấm linh chi) trên mỗi kg thể trọng cho một lần dùng. Các thí nghiệm trên động vật đã kiểm tra liều lượng lên đến 38g tinh chất( tương đương 570g nấm linh chi) trên mỗi kg thể trọng.
			</div>
		
		</div>
		
		<div data-cat="Prenancy-Lactation">
			<h2>Tac dung cua nam linh chi đối với Phụ nữ có thai và cho con bú </h2>
			<div>
			Chưa có các báo cáo nghiên cứu về hiệu quả và tác động của nấm linh chi lên phụ nữ có thai và cho con bú.
			</div>
		
		</div>
	
		<div data-cat="Reference">
			<h2>Các tài liệu tham chiếu cho các thông tin trên </h2>
			<div>
			<p>Thông tin trên được dịch từ <a href="http://www.drugs.com/npp/reishi-mushroom.html#ref36" target="blank">http://www.drugs.com/npp/reishi-mushroom.html#ref36</a> bởi NAGAO </p>
<h3>Bibliography</h3>

1. Liu J , Shimizu K , Konishi F , Kumamoto S , Kondo R . The anti-androgen effect of ganoderol B isolated from the fruiting body of Ganoderma lucidum . Bioorg Med Chem . 2007 ; 15 ( 14 ): 4966-4972 .<br/>
2. Lininger SW, Wright JV, et al, eds. The Natural Pharmacy . Rocklin, CA: Prima Publishing; 1998:303-304.<br/>
3. Zhou X , Lin J , Yin Y , Zhao J , Sun X , Tang K . Ganodermataceae: natural products and their related pharmacological functions . Am J Chin Med . 2007 ; 35 ( 4 ): 559-574 .<br/>
4. Matsumoto K . The Mysterious Reishi Mushroom . Santa Barbara, CA: Woodbridge Press Publishing; 1979 .<br/>
5. Li EK , Tam LS , Wong CK , et al. Safety and efficacy of Ganoderma lucidum (lingzhi) and San Miao San supplementation in patients with rheumatoid arthritis: a double-blind, randomized, placebo-controlled pilot trial . Arthritis Rheum . 2007; 57 ( 7 ): 1143-1150 .<br/>
6. Paterson RR . Ganoderma—a therapeutic fungal biofactory . Phytochemistry . 2006 ; 67 ( 18 ): 1985-2001 .<br/>
7. Miyazaki T , Nishijima M . Studies on fungal polysaccharides. XXVII. Structural examination of a water-soluble, antitumor<br/> polysaccharide of Ganoderma lucidum . Chem Pharm Bull . 1981 ; 29 ( 12 ): 3611-3616 .<br/>
8. Kohda H , Tokumoto W , Sakamoto K , et al. The biologically active constituents of Ganoderma lucidum (Fr.) Karst. Histamine release-inhibitory triterpenes . Chem Pharm Bull (Tokyo). 1985 ; 33 ( 4 ): 1367-1374 .<br/>
9. Zhu M , Chang Q , Wong LK , Chong FS , Li RC . Triterpene antioxidants from Ganoderma lucidum . Phytother Res . 1999 ; 13 ( 6 ): 529-531 .<br/>
10. Hajjaj H , Macé C , Roberts M , Niederberger P , Fay LB . Effect of 26-oxygenosterols from Ganoderma lucidum and their activity as cholesterol synthesis inhibitors . Appl Environ Microbiol . 2005 ; 71 ( 7 ): 3653-3658 .<br/>
11. Tang W , Liu JW , Zhao WM , Wei DZ , Zhong JJ . Ganoderic acid T from Ganoderma lucidum mycelia induces mitochondria mediated apoptosis in lung cancer cells . Life Sci . 2006 ; 80 ( 3 ): 205-211 .<br/>
12. Mizushina Y , Takahashi N , Hanashima L , et al. Lucidenic acid O and lactone, new terpene inhibitors of eukaryotic DNA polymerases from a basidiomycete, Ganoderma lucidum . Bioorg Med Chem . 1999 ; 7 ( 9 ): 2047-2052 .<br/>
13. Cheong J , Jung W , Park W . Characterization of an alkali-extracted peptidoglycan from Korean Ganoderma lucidum . Arch Pharm Res . 1999 ; 22 ( 5 ): 515-519 .<br/>
14. D'Souza TM , Merritt CS , Reddy CA . Lignin-modifying enzymes of the white rot basidiomycete Ganoderma lucidum . Appl Environ Microbiol . 1999 ; 65 ( 12 ): 5307-5313 .<br/>
15. Lin ZB , Zhang HN . Anti-tumor and immunoregulatory activities of Ganoderma lucidum and its possible mechanisms. Acta Pharmacol Sin . 2004 ; 25 ( 11 ): 1387-1395 .<br/>
16. Yuen JW , Gohel MD . Anticancer effects of Ganoderma lucidum : a review of scientific evidence . Nutr Cancer . 2005 ; 53 ( 1 ): 11-17 .<br/>
17. Kuo MC , Weng CY , Ha CL , Wu MJ . Ganoderma lucidum mycelia enhance innate immunity by activating NF-kappaB . J Ethnopharmacol . 2006 ; 103 ( 2 ): 217-222 .<br/>
18. Müller CI , Kumagai T , O'Kelly J , Seeram NP , Heber D , Koeffler HP . Ganoderma lucidum causes apoptosis in leukemia, lymphoma and multiple myeloma cells . Leuk Res . 2006 ; 30 ( 7 ): 841-848 .<br/>
19. Sliva D . Ganoderma lucidum in cancer research . Leuk Res . 2006 ; 30 ( 7 ): 767-768 .<br/>
20. Kim HS , Kacew S , Lee BM . In vitro chemopreventive effects of plant polysaccharides ( Aloe barbadensis miller , Lentinus edodes , Ganoderma lucidum and Coriolus versicolor ) . Carcinogenesis . 1999 ; 20 ( 8 ): 1637-1640 .<br/>
21. Gao Y , Gao H , Chan E , et al. Antitumor activity and underlying mechanisms of ganopoly, the refined polysaccharides extracted from Ganoderma lucidum , in mice . Immunol Invest . 2005 ; 34 ( 2 ): 171-198 .<br/>
22. Pang X , Chen Z , Gao X , et al. Potential of a novel polysaccharide preparation (GLPP) from Anhui-grown Ganoderma lucidum in tumor treatment and immunostimulation . J Food Sci . 2007 ; 72 ( 6 ): S435-S442 .<br/>
23. Zhu XL , Lin ZB . Effects of Ganoderma lucidum polysaccharides on proliferation and cytotoxicity of cytokine-induced killer cells . Acta Pharmacol Sin . 2005 ; 26 ( 9 ): 1130-1137 .<br/>
24. Lin YL , Lee SS , Hou SM , Chiang BL . Polysaccharide purified from Ganoderma lucidum induces gene expression changes in human dendritic cells and promotes T helper 1 immune response in BALB/c mice . Mol Pharmacol . 2006 ; 70 ( 2 ): 637-644 .<br/>
25. Lin YL , Liang YC , Lee SS , Chiang BL . Polysaccharide purified from Ganoderma lucidum induced activation and maturation of human monocyte-derived dendritic cells by the NF-kappaB and p38 mitogen-activated protein kinase pathways . J Leukoc Biol . 2005 ; 78 ( 2 ): 533-543 .<br/>
26. Li YQ , Wang SF . Anti-hepatitis B activities of ganoderic acid from Ganoderma lucidum . Biotechnol Lett . 2006 ; 28 ( 11 ): 837-841 .<br/>
27. Lin KI , Kao YY , Kuo HK , et al. Reishi polysaccharides induce immunoglobulin production through the TLR4/TLR2-mediated induction of transcription factor Blimp-1 . J Biol Chem . 2006 ; 281 ( 34 ): 24111-24123 .<br/>
28. Chan WK , Lam DT , Law HK , et al. Ganoderma lucidum mycelium and spore extracts as natural adjuvants for immunotherapy . J Altern Complement Med . 2005 ; 11 ( 6 ): 1047-1057 .<br/>
29. Lin ZB . Cellular and molecular mechanisms of immuno-modulation by Ganoderma lucidum . J Pharmacol Sci . 2005 ; 99 ( 2 ): 144-153 .<br/>
30. Yue GG , Fung KP , Tse GM , Leung PC , Lau CB . Comparative studies of various ganoderma species and their different parts with regard to their antitumor and immunomodulating activities in vitro . J Altern Complement Med . 2006 ; 12 ( 8 ): 777-789 .<br/>
31. Tang W , Gao Y , Chen G , et al. A randomized, double-blind and placebo-controlled study of a Ganoderma lucidum polysaccharide extract in neurasthenia . J Med Food . 2005 ; 8 ( 1 ): 53-58 .<br/>
32. Cao QZ , Lin ZB . Ganoderma lucidum polysaccharides peptide inhibits the growth of vascular endothelial cell and the induction of VEGF in human lung cancer cell . Life Sci . 2006 ; 78 ( 13 ): 1457-1463 .<br/>
33. Stanley G , Harvey K , Slivova V , Jiang J , Sliva D . Ganoderma lucidum suppresses angiogenesis through the inhibition of secretion of VEGF and TGF-beta1 from prostate cancer cells . Biochem Biophys Res Commun . 2005 ; 330 ( 1 ): 46-52 .<br/>
34. Kim KC , Kim JS , Son JK , Kim IG . Enhanced induction of mitochondrial damage and apoptosis in human leukemia HL-60 cells by the Ganoderma lucidum and Duchesnea chrysantha extracts . Cancer Lett . 2007 ; 246 ( 1-2 ): 210-217 .<br/>
35. Gao Y , Tang W , Dai X , et al. Effects of water-soluble Ganoderma lucidum polysaccharides on the immune functions of patients with advanced lung cancer . J Med Food . 2005 ; 8 ( 2 ): 159-168 .<br/>
36. Gao Y , Zhou S , Jiang W , Huang M , Dai X . Effects of ganopoly (a Ganoderma lucidum polysaccharide extract) on the immune functions in advanced-stage cancer patients . Immunol Invest . 2003 ; 32 ( 3 ): 201-215 .<br/>
37. Kim KC , Kim IG . Ganoderma lucidum extract protects DNA from strand breakage caused by hydroxyl radical and UV irradiation . Int J Mol Med . 1999 ; 4 ( 3 ): 273-277 .<br/>
38. Cheuk W , Chan JK , Nuovo G , Chan MK , Fok M . Regression of gastric large B-Cell lymphoma accompanied by a florid lymphoma-like T-cell reaction: immunomodulatory effect of Ganoderma lucidum (Lingzhi)? Int J Surg Pathol . 2007 ; 15 ( 2 ): 180-186 .<br/>
39. Morigiwa A , Kitabatake K , Fujimoto Y , Ikekawa N . Angiotensin converting enzyme-inhibitory triterpenes from Ganoderma lucidum . Chem Pharm Bull (Tokyo) . 1986 ; 34 ( 7 ): 3025-3028 .<br/>
40. Su CY , Shiao MS , Wang CT . Differential effects of ganodermic acid S on the thromboxane A2-signaling pathways in human platelets . Biochem Pharmacol . 1999 ; 58 ( 4 ): 587-595 .<br/>
41. Wang S , et al. The role of Ganoderma lucidum in immunopotentiation: Effect on cytokine release from human macrophages and T-lymphocytes. In: Program and Abstracts of the 1994 International Symposium on Ganoderm Research . Beijing: Beijing Medical University.<br/>
42. Chang H-M, But P, eds. Pharmacology and Applications of Chinese Materia Medica . Vol. 1. Singapore: World Scientific; 1986.<br/>
43. Byun S , Rim IH . Studies on the concurrent administration of Ganoderma lucidum extract and glutathione on liver damage induced by carbon tetrachloride in rats . J Pharm . 1987 ; 31 : 133-139 .<br/>
44. Ho YW , Yeung JS , Chiu PK , et al. Ganoderma lucidum polysaccharide peptide reduced the production of proinflammatory cytokines in activated rheumatoid synovial fibroblast . Mol Cell Biochem . 2007 ; 301 ( 1-2 ): 173-179 .<br/>
45. Xi Bao Y , Kwok Wong C , Kwok Ming Li E , et al. Immunomodulatory effects of lingzhi and san-miao-san supplementation on patients with rheumatoid arthritis . Immunopharmacol Immunotoxicol . 2006 ; 28 ( 2 ): 197-200 .<br/>
46. Eo SK , Kim YS , Lee CK , Han SS . Antiherpetic activities of various protein bound polysaccharides isolated from Ganoderma lucidum . J Ethnopharmacol . 1999 ; 68 ( 1-3 ): 175-181 .<br/>
47. Eo SK , Kim YS , Lee CK , Han SS . Antiviral activities of various water and methanol soluble substances isolated from Ganoderma lucidum . J Ethnopharmacol . 1999 ; 68 ( 1-3 ): 129-136 .<br/>
48. Wicks SM , Tong R , Wang CZ , et al. Safety and tolerability of Ganoderma lucidum in healthy subjects: a double-blind randomized placebo-controlled trial . Am J Chin Med . 2007 ; 35 ( 3 ): 407-414 .<br/>
49. Kwok Y , Ng KF , Li CC , Lam CC , Man RY . A prospective, randomized, double-blind, placebo-controlled study of the platelet and global hemostatic effects of Ganoderma lucidum (Ling-Zhi) in healthy volunteers . Anesth Analg . 2005 ; 101 ( 2 ): 423-426 .<br/>
Copyright © 2009 Wolters Kluwer Health			
			</div>
		
		</div>
	
	</div>
	
	
	<!--  Kết thúc phần thân-->
<?php include "php/footer.php"; $db = NULL; ?>

</body>
</html>
