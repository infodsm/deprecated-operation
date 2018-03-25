#include <stdio.h>

int strlen(char a[])
{
	int i = 0;

	while (a[i] != '\0')
	{
		i++;
	}

	return i;
}
	
int exam_01()
{
	char a[] = "Hello!";
	int n = 0;
	
	n = strlen(a);

	printf("%d\n", n);
}