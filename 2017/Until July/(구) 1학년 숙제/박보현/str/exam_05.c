#include <stdio.h>

char strncpy(char *a, char *b, int c)
{
	int i = 0;

	while (i < c)
	{
		a[i] = b[i];
		i++;
	}
}

int exam_05()
{
	char a[10] = { 0 }, b[] = "JAINA";

	strncpy(&a, &b, 3);

	printf("%s\n", a);
}