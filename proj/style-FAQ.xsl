<?xml version='1.0' encoding='UTF-8' ?>

<!--xsl stylesheet declaration with xsl namespace: Namespace tells the xslt proccessor about which element is to be processed and which is used for output purposes only-->
<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<!--xsl template declaration:template tells the xslt processor about the section of xml document which is to be formatted. It takes an XPath expression.-->	
	<xsl:template match='/'>
	
<!-- html tags: used for formatting purpose. Browser will render them and processor will skip them-->
		<html>
			<body> 
				<h1> Frequently Asked Questions </h1>
				
				<div>
				
					<xsl:for-each select='FAQ/parent/questions'>
						<a href='#answers'>
						<div style='background-color:teal; color:white; padding:4px'>
							<p style='color:white'> <xsl:value-of select='ques'/> </p>
						</div>	
						</a>
						<br/> 
					</xsl:for-each>	
					
				</div>
				
				<h1> Answers </h1>
				
				<div>
					<xsl:for-each select='FAQ/parent/answers'>
						<div style='background-color:teal; color:white; padding:4px'>
								<p> <xsl:value-of select='ques'/> </p>
								
						</div>	
						<p id='answers'> <xsl:value-of select='ans'/> </p>

						<br/> 
					</xsl:for-each>			
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>

<!---->

<!---->