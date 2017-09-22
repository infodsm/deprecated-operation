#include <stdio.h>

int strcmp(char a[], char b[])
{
	int i;

	for (i = 0; a[i] != '\0' || b[i] != '\0'; i++)
	{
		if (a[i] != b[i]) return 1;
	}
	return 0;
}

int exam_06()
{
	char a[] = "JAINA", b[] = "JAINA";
	int n = 0;

	n = strcmp(a, b);

	printf("%d\n", n);

	if (n == 1)
		printf("두 문자열이 다릅니다\n");
	else if (n == 0)
		printf("두 문자열이 같습니다\n");
	
}