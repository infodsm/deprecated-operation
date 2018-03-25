#include <stdio.h>

char strcpy(char *a, char *b)
{
	int i = 0;

	while (b[i] != '\0')
	{
		a[i] = b[i];
		i++;
	}
}

int exam_02()
{
	char a[10] = { 0 }, b[] = "JAINA";

	strcpy(&a, &b);

	printf("%s\n", a);
}